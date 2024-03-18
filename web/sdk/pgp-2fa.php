<?php

/*
Description: Two-Factor Authentication for the web with GnuPG
Copyright: 2024 Luan Schons Griebler
*/

class RandomByteGenerationException extends Exception {}

/**
 * Generates a random secret passphrase with a default length of 15 characters.
 *
 * @param int $length The length of the random passphrase
 * @return string The generated random passphrase
 * @throws RandomByteGenerationException If unable to generate random bytes
 */
function generateRandomPassphrase(int $length = 15): string {
    try {
        $randomBytes = random_bytes($length);
    } catch (Exception $e) {
        throw new RandomByteGenerationException('Failed to generate random bytes.');
    }

    return bin2hex($randomBytes);
}

class PGP2FA {

    // Unencrypted secret
    private string $secretPassphrase;

    /**
     * Generates a secret passphrase, hashes it, and saves it securely.
     *
     * @throws RandomByteGenerationException If unable to generate random bytes for the passphrase
     * @throws Exception If hashing the passphrase fails
     */
    public function generateSecretPassphrase(): void {
        // Generate unencrypted secret passphrase
        $secretPassphrase = generateRandomPassphrase();

        // Hash the passphrase with bcrypt
        $hashedPassphrase = password_hash($secretPassphrase, PASSWORD_BCRYPT);
        if ($hashedPassphrase === false) {
            throw new Exception('Failed to hash the secret passphrase.');
        }

        // Save the hashed passphrase
        $_SESSION['pgp-secret-hash'] = $hashedPassphrase;

        // Save the unencrypted passphrase locally for safety
        $this->secretPassphrase = $secretPassphrase;
    }

    /**
     * Encrypts the secret passphrase using PGP public key.
     *
     * @param string $publicKey The public key used for encryption
     * @return string|null The encrypted secret passphrase or null if encryption fails
     * @throws Exception If GnuPG extension is not available or encryption fails
     */
    public function encryptSecretPassphrase(string $publicKey): ?string {
        // Check if GnuPG extension is available
        if (!extension_loaded('gnupg')) {
            throw new Exception('GnuPG extension is not available.');
        }

        // Set GnuPG homedir to /tmp
        putenv("GNUPGHOME=/tmp");

        // Create a new GnuPG instance
        $gnupg = new gnupg();
        if ($gnupg === false) {
            throw new Exception('Failed to create GnuPG instance.');
        }

        // Import the provided public key
        $keyInfo = $gnupg->import($publicKey);
        if (!$keyInfo) {
            throw new Exception('Failed to import the public key.');
        }

        // Add the imported key for encryption
        $gnupg->addencryptkey($keyInfo['fingerprint']);

        // Encrypt the secret passphrase to a PGP message
        $encryptedPassphrase = $gnupg->encrypt($this->secretPassphrase);
        if (!$encryptedPassphrase) {
            throw new Exception('Failed to encrypt the secret passphrase.');
        }

        // Clear the encryption key
        $gnupg->clearencryptkeys();

        // Return the encrypted PGP message
        return $encryptedPassphrase;
    }

    /**
     * Compares the user input with the saved secret passphrase hash.
     *
     * @param string $userInput The user input to be compared
     * @return bool True if the user input matches the saved hash, otherwise false
     */
    public function compareSecretPassphrase(string $userInput): bool {
        // Compare the user input with the saved passphrase hash
        return password_verify($userInput, $_SESSION['pgp-secret-hash'] ?? '');
    }
}
?>
