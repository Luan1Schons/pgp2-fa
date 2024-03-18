# Two-Factor Authentication with GnuPG

This project provides a simple implementation of Two-Factor Authentication (2FA) for web applications using GnuPG (GNU Privacy Guard). It generates a random passphrase, encrypts it with a provided PGP public key, and compares it with user input for authentication.
## How It Works

1. **Generating a Secret Passphrase:**
    - The system generates a random passphrase of default length 15 characters.
    - The passphrase is hashed using bcrypt for secure storage.
    - The hashed passphrase is saved in the user's session, and the unhashed passphrase is stored locally within the application.

2. **Encrypting the Secret Passphrase:**
    - The unhashed passphrase is encrypted using a provided PGP public key.
    - The encrypted passphrase is used for verification during authentication.

3. **Authentication:**
    - During login, the user provides their passphrase.
    - The system compares the provided passphrase with the hashed passphrase stored in the session.
    - If the passphrases match, authentication is successful.

## Running the Project with Docker Compose

To run this project using Docker Compose, follow these steps:

1. Clone this repository to your local machine:

```console
git clone <repository_url>
```

2. Navigate to the project directory:
```console
cd <project_directory>
 ```

3. Build and start the Docker containers:
```console
docker-compose up --build
 ```

Once the containers are up and running, you can access the web application at http://localhost:8082.

Use the provided interface to interact with the Two-Factor Authentication functionality.
## FAQ

#### How Gerate PGP Public
```console
gpg --full-generate-key
gpg --export --armor your_email@example.com > public_key.asc
```

#### How Decrypt PGP Generated Secret?
Copy the cryptographed message and paste in encrypted_message.asc file, after run this command to have access to generated hash by application
```console
gpg --decrypt encrypted_message.asc
```


## Authors

- [@Luan1Schons](https://github.com/Luan1Schons/)

## License

[APACHE 2.0](https://choosealicense.com/licenses/apache-2.0/)