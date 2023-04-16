<?php

namespace SELF\src;

use App\Domains\Auth\Repository\AuthQuery;
use DateInterval;
use DateTime;
use SELF\src\Auth\AuthenticatableRecord;
use SELF\src\Auth\AuthRecord;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

class Authenticator
{
    public const AUTH_SESSION_KEY = 'authToken';

    protected static self $instance;

    protected Container $container;

    protected Session $session;

    protected AuthRecord $authModel;

    protected AuthQuery $authQuery;

    public function __construct()
    {
        $this->container = Container::getInstance();
        $this->session = $this->container->resolve(Session::class);
        $this->authModel = $this->getAuthModel();
        $this->authQuery = $this->getAuthQuery();
    }

    public static function getInstance(): static
    {
        if (! isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Override this with relevant auth model.
     *
     * @return AuthRecord
     */
    public function getAuthModel(): AuthRecord
    {
        return new AuthRecord();
    }

    /**
     * Override this with relevant auth query.
     *
     * @return AuthQuery
     */
    public function getAuthQuery(): AuthQuery
    {
        return new AuthQuery();
    }

    /**
     * Checks if current user is authenticated.
     *
     * @return bool
     */
    public static function check(): bool
    {
        return self::getInstance()->getAuthRecordFromSession() !== null;
    }

    /**
     * Creates new auth record and puts token in the session.
     *
     * @param AuthenticatableRecord $authenticatable
     * @return void
     */
    public static function login(AuthenticatableRecord $authenticatable): void
    {
        $instance = self::getInstance();
        $authRecord = $instance->createAuthRecord($authenticatable);
        $instance->createNewAuthSession($authRecord);
    }

    protected function getAuthRecordFromAuthenticatable(AuthenticatableRecord $authenticatable): ?AuthRecord
    {
        $query = $this->authQuery;
        $model = $this->authModel;
        $session = $this->session;

        /**
         * @var AuthRecord | null $record
         */
        $record = $query
            ->filterBy($model->getTokenColumn(), Criteria::EQUALS, $session->get(self::AUTH_SESSION_KEY))
            ->filterBy($model->getIdentifierColumn(), Criteria::EQUALS, $authenticatable->getIdentifier())
            ->filterBy($model->getExpiresColumn(), Criteria::LESS_THAN, new DateTime())
            ->findOne();

        return $record;
    }

    protected function getAuthRecordFromSession(): ?AuthRecord
    {
        $query = $this->authQuery;
        $model = $this->authModel;

        /**
         * @var AuthRecord | null $record
         */
        $record = $query
            ->filterBy($model->getTokenColumn(), Criteria::EQUALS, $this->session->get(self::AUTH_SESSION_KEY))
            ->filterBy($model->getExpiresColumn(), Criteria::GREATER_THAN, new DateTime())
            ->findOne();

        return $record;
    }

    private function createNewAuthSession(AuthRecord $record): void
    {
        /**
         * @var Session $session
         */
        $session = $this->container->resolve(Session::class);

        $session->set(
            self::AUTH_SESSION_KEY,
            $record->token,
        );
    }

    private function createAuthRecord(AuthenticatableRecord $authenticatable): AuthRecord
    {
        $token = Token::generate();
        $model = $this->authModel;

        $model->set($model->getIdentifierColumn(), $authenticatable->getIdentifier());
        $model->set($model->getTokenColumn(), $token);
        $model->set($model->getExpiresColumn(), (new DateTime())->add(DateInterval::createFromDateString('1 day')));

        $model->save();

        return $model;
    }
}