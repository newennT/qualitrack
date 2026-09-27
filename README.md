# Qualitrack

## Description
Qualitrack is an internal web application designed to monitor the compliance of cleaning operations across different sites. The application allows employees to carry out on-site audits, assess individual cleaning operations, document non-compliant results with photos, and send a complete audit report to the site manager.

Qualitrack was developed as an internal business application to provide a structured and traceable way of monitoring cleaning quality. The application replaces a more manual audit process with a centralized digital workflow, making it easier to document issues, calculate compliance results, and communicate them to the people responsible for each site.

## Features
- Site : Select a site and carry out a structured audit of its cleaning operations.
- Operation compliance : Mark each operation as compliant or non-compliant.
- Photo evidence : Attach a photo to document non-compliant cleaning operations.
- Compliance score : Automatically calculate an overall compliance score at the end of the audit.
- Audit reports : Send the completed audit and its results to the site manager.
- Audit history : Keep track of completed audits and their results.

## Audit workflow
1. The employee selects the site to audit.
2. Each cleaning operation is reviewed individually.
3. The employee marks the operation as compliant or non-compliant.
4. When an operation is non-compliant, a photo can be added as evidence.
5. Once all operations have been reviewed, Qualitrack calculates the site's overall compliance score.
6. The completed audit is sent to the site manager.

## Administration interface
Administrators can perform full CRUD operations (Create, Read, Update, Delete) on the main business entities: sites, cleaning operations, audits, auditors. This back-office provides a centralized way to maintain the application's business data and configure the audit workflow without directly modifying the database.

## Getting Started
- Clone repo `git clone https://github.com/newennT/qualitrack.git`
- `cd qualitrack`
- Open Docker
- Run `docker compose build --no-cache` to build fresh images
- Run `docker compose up --pull always -d --wait` to set up 
- Run `npm install` and `npm run build` to compile assets
- Run `php bin/console doctrine:fixtures:load` to load fixtures
- Open localhost

## Technologies
- Symfony : application framework
- Twig : server-side rendered user interface
- Doctrine ORM : database access
- Symfony Forms : form handling and validation
- Symfony Security : user authentication and access control
- SQL : relational data storage
- JavaScript : dynamic behavior
- Sass : styling and CSS preprocessing

## Code structure
### Audit workflow
AuditController handles the main user-facing workflow. It guides auditors through the different stages of an audit. The workflow relies on Doctrine entities and repositories for data management, Symfony Forms for input and validation, and dedicated services for technical operations such as email delivery and PDF generation.

### Administration
The Controller/Admin namespace contains the controllers dedicated to managing the application's reference and audit data. It provides CRUD interfaces for: Sites, Cleaning operations, Auditors, Audits. Each resource has its own controller and Symfony Form, while Doctrine repositories handle the associated database queries.

### Other components
- Entities model the application's core business objects and their relationships.
- Repositories handle database queries through Doctrine ORM.
- Forms manage data input and validation.
- Services provide reusable technical functionality, including email and PDF generation.
- Factories and Fixtures provide development and test data.
- Twig templates, JavaScript and Sass handle the server-rendered interface and client-side interactions.

## Tests
- Run `php bin/phpunit`

## Context
This repository is an anonymized version of a professional project. Company names, identifying information, and production data have been removed or replaced for portfolio purposes. The application was originally developed for internal use and is not intended as a publicly deployable product.

## Screenshots
<img width="831" height="953" alt="-qualitrack-03" src="https://github.com/user-attachments/assets/2c05522a-3d44-47b1-8f1c-fd86e14a5da2" />

<img width="831" height="1125" alt="-qualitrack-04" src="https://github.com/user-attachments/assets/d18124c9-800b-4821-9c5e-e118eadd1930" />

<img width="831" height="818" alt="-qualitrack-01" src="https://github.com/user-attachments/assets/d4c41417-7d2a-4018-938b-6ca14ff91283" />









