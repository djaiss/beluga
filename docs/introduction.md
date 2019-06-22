# Introduction

## Beluga in 1 minute

Beluga is a new kind of human resource software. Unlike other robotic HR tools, it has been designed to mimic what happens in the real world. Its main goals are to:
- help managers be more empathetic,
- help companies be better at managing people's dreams and ambitions,
- help employees communicate with their employers,
- give users and companies a complete control over their data,
- reduce the use of LinkedIn and all the bullshit associated with it in today's world.

Beluga is open source and can be installed on a server that you own if you so desire.

In terms of user experience, the software aims to be simple to use with the minimum amount of configuration. Design is not a priority - user experience is.

To use the software, users have to pay a fair fixed monthly fee, regardless of the size of your team. We do not sell your data, don't use ads nor use external analytical services. You can export your data at anytime or use the API without restrictions.

Technically, the software is developed with boring, proven, predictible, easy to maintain technologies that make the tool fast and secure. We want to create a product useful for our users, not something that is technologically exciting.

## Beluga in 10 minutes

Beluga is built around the notion that while companies own data about their employees, users have complete control over which data they give to companies.

## A note about encryption

Beluga tries to be as secure as possible. Being completely secure is hard but we do our best to make it so.

Beluga uses two kind of passwords: the password used to sign in to your account, and a Secret Key.

The password is stored on our servers and lets users access their accounts. It is encrypted by us, so we know how to decrypt it.

The Secret Key, on the other hand, is what we use to actually encrypt our users personal data. We never store this key on our servers. That means if the user forgets his secret key, we have no mean to recover the data. They would be lost forever.

In terms of how it’s done behind the scene, it’s actually simple.

When users create an account, we give them their secret key and ask them to store it somewhere. Then, when they sign in, they need to tell us, in the browser, what is their secret key. We store this key in the cookies of the browser. Each time we need to read or write a data, we send the secret key as a parameter to the query sent to the server. We perform the encryption/decryption on the server, and never ever store this key.

Performing the encryption process on the server is not great, as the secret key is actually transmitted in the HTTP query. Even though it’s served through HTTPS, it could still be captured. But even if this data is captured, it would only affect one user, and not the entire database. So we can say it’s reasonably secure. We don't want to make this huge effort of encrypting everything in the browser before sending the data.

This kind of security is great for users, but it comes with limitations for developers:
- it becomes really hard to perform a search,
- it becomes almost impossible to notify users for instance, especially if we do encrypt their email addresses,
- we can't sort data.