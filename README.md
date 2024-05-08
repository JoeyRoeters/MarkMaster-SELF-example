# SELF - Superior Elegant Lightweight Framework

SELF is a custom-built PHP framework designed from scratch to meet the stringent requirements of modern web development. Developed as part of a university project, SELF aims to provide a streamlined, secure, and efficient way to build web applications, adhering closely to PSR standards. The framework is been build accordingly to the specifications for the course, it's not build on a large scale, like symfony or laravel; it's basic.

## Background

As a capstone project for Web Technology II, we were tasked with creating a PHP backend frameworks. The primary application developed using SELF is a tentamen (exam) tracking system, akin to Osiris, which allows students to register for exams, view their grades, while enabling professors and administrators to manage exam records and results efficiently.

## Features

- **Compliance with PSR Standards:** Ensures interoperability and high quality of code.
- **Secure:** Implements robust security measures to prevent common vulnerabilities such as SQL injection and XSS.
- **Lightweight and Fast:** Designed to be efficient and performant even under load.
- **No External Dependencies:** SELF is built entirely using native PHP, ensuring full control over all aspects of the framework.
- **Intuitive Routing System:** Clear and flexible routing that adheres to PSR-15.
- **Middleware and Event Handling:** Utilizes a middleware approach based on PSR-14 to handle requests dynamically.
- **ORM Support:** Custom ORM built to facilitate easy and secure database interactions.
- **Template Engine:** Simple yet powerful templating that supports variables and basic expressions.
- **Dependency Injection:** Implements PSR-11 compliant dependency injection container to manage class dependencies.

## Structure:

The SELF framework is located is located in '/libraries/SELF/', the application MarkMaster is been build out in '/App/' with it's Assets in '/Assets/'.
