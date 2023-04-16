<?php
namespace App\Domains\User\Repository;

use App\Domains\Class\Repository\StudentClassQuery;
use App\Domains\Role\Repository\Role;
use App\Domains\Role\Repository\RoleQuery;
use App\Domains\Role\Repository\RoleUser;
use App\Domains\Role\Repository\RoleUserQuery;
use App\Enums\RoleEnum;
use DateTime;
use SELF\src\Auth\AuthenticatableRecord;
use SELF\src\HelixORM\HelixObjectCollection;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * Class User
 * @package App\Domains\User\Repository
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 * @method string|null getName()
 * @method string|null getEmail()
 * @method string|null getPassword()
 * @method DateTime|null getCreatedAt()
 * @method DateTime|null getUpdatedAt()
 * @method User setName(string $name)
 * @method User setEmail(string $email)
 * @method User setPassword(string $password)
 * @method User setCreatedAt(DateTime $createdAt)
 * @method User setUpdatedAt(DateTime $updatedAt)
 */
class User extends AuthenticatableRecord
{
    protected string $table = 'users';

    public function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, null, false)->setPrimaryKey(true),
            TableColumn::create('name'),
            TableColumn::create('email'),
            TableColumn::create('password'),
            TableColumn::create('created_at', ColumnType::DATETIME)->autoTimestamp(),
            TableColumn::create('updated_at', ColumnType::DATETIME)->autoTimestamp(),
        ];
    }

    /**
     * @return Role[]
     */
    public function getRoles(): array
    {
        $roleUsers = RoleUserQuery::create()
            ->filterByUserId($this->id)
            ->find()
            ->get();

        if (empty($roleUsers)) {
            return [];
        }

        $roleIds = array_map(
            fn (RoleUser $roleUser) => $roleUser->role_id,
            $roleUsers,
        );

        return RoleQuery::create()
            ->filterById($roleIds, Criteria::IN)
            ->find()
            ->get();
    }

    public function hasRole(Role $role): bool
    {
        foreach ($this->getRoles() as $foundRoles) {
            if ($role->id === $foundRoles->id) {
                return true;
            }
        }

        return false;
    }

    public function appendRole(Role $role): void
    {
        (new RoleUser())
            ->setRoleId($role->id)
            ->setUserId($this->id)
            ->save();
    }

    public function getIdentifier(): string
    {
        return $this->id;
    }

    public function getHashedPassword(): string
    {
        return $this->password;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RoleQuery::findOrCreate(RoleEnum::ADMIN));
    }

    public function isStudent(): bool
    {
        return $this->hasRole(RoleQuery::findOrCreate(RoleEnum::STUDENT));
    }

    public function isTeacher(): bool
    {
        return $this->hasRole(RoleQuery::findOrCreate(RoleEnum::TEACHER));
    }

    public function getClasses(): HelixObjectCollection
    {
        return StudentClassQuery::create()
            ->filterByStudentId($this->id)
            ->find();
    }

    public function export(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->isAdmin(),
            'is_student' => $this->isStudent(),
            'is_teacher' => $this->isTeacher(),
        ];
    }
}