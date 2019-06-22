### Encryption

Most of the fields in the database need to be encrypted before being saved. However, some are not.
To make the distinction between those two types of fields, the names of the fields that are encrypted start with `enc_`. That way, in the code, you can know immediately if the data needs to be decrypted before being displayed to the user.

### Migrations

Migrations shall not have a `down` method.