# Cryptology

This is an online version of the popular board game Codenames.
The gameplay is tweak a bit to better suit with the medium of
play -- online.

### Specifications / Infrastructure Information

- Docker (Nginx)
- Laravel Framework
- MySQL
- React

### Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

#### Prerequisites

Make sure you have installed the following on your local machine:

- Git
- Virtual Box
- Node.js & NPM
- Docker & Docker Compose

#### Installing

1. Clone the repo

```
git clone https://gitlab.com/jericho6367/cryptology.git
cd cryptology
```

2. Setup the .env file for Docker

```
cp .env.example .env
```

3. Build the containers

```
docker-compose build
```

4. Start the containers

```
docker-compose up -d

```

#### Setting up Backend

1. Login to the PHP-FPM container

```
docker exec -it docker_php bash
```

Note: 'docker_php' is the default name of the container. If you changed the value of PROJECT_NAME inside the .env file,
then you need to change 'docker' to the value of PROJECT_NAME.

2. Install the dependencies

```
composer install
```

3. Setup Laravel

```
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan passport:install
```

Copy the newly generated client secret of Client 2 from the `passport:install` command.

Once everything is done, you can now exit from bash.

```
exit
```

#### Setting up Frontend

1. Go into frontend's directory

```
  cd sites/frontend
```

2. Install the dependencies

```
npm install
```

3. Setup .env

```
cp .env.example .env
```

Open the .env file edit the line below

```
REACT_APP_CLIENT_SECRET=(Paste the client secret here that you've copied earlier)
```

4. Start the server

```
npm start
```

5. Open http://locahost:3000
6. Explore and test.
