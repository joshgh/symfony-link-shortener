# symfony-link-shortener

This app creates shortlinks for urls.

This project is set up to be run using Docker and Docker Compose. It includes an apache webserver container and a mysql container.

If only needing to run this app and not do any development work, uncomment the vendor volume under the 'web' service in the docker-compose file before spinning it up (`docker-compose up`). This will allow the vendor directory inside the docker container to be used without any additional composer install.

If developing on this app, leave the vendor volume commented out and run composer install within the php directory. This will install the dependencies locally and make them visible to the editor for code hints.

There is a dbschema.sql file in the root of the repository to set up the database.

Once ready to deploy, the Symfony app is contained in the php directory of this repository. The vendor directory is not included in this repo so a composer install will need to be run wherever the code is deployed.

The database connection parameters that work with the docker setup are in the .env file, they can be overridden by creating a .env.local file

