## Working with encrypted data

### In the database

Most of the fields in the database need to be encrypted before being saved. However, some are not.
To make the distinction between those two types of fields, the names of the fields that are encrypted start with `enc_`. That way, in the code, you can know immediately if the data needs to be decrypted before being displayed to the user.

### Encrypting data

Data is encrypted on the server, with the secret key from the user's browser given as parameter. The secret key is never saved on the server. However we do save a hash of this secret key to make sure we have the right secret key before encrypting/decrypting the data. The secret key comes from an encrypted cookie, and each request made on the server carries this key.

Routes are protected with a middleware called `secret.key` which verifies that the user's secret key is saved on a cookie before processing any request, as well as that the key is the right one (by comparing the hashes).

Fields that should be encrypted must be encrypted through the EncryptionHelper helper, like so

```
EncryptionHelper::encrypt($dataToEncrypt, $secretKey)
```

As far as reading encrypted data goes, as long as fields are properly named with `enc_`, they will be automatically decrypted when calling the EncryptionHelper helper, like

```
EncryptionHelper::removeEncryptionForClient($objectToDecrypt, $secretKey)
```

This method decrypts both an object and a collection,

### Migrations

Migrations shall not have a `down` method.