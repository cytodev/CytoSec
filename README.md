# CytoSec
a crypto client written in PHP

#### What is this?
CytoSec is a crypto client written in PHP. it can be used to send secured messages along a network (not yet implemented).
I am aiming to rewrite this client in C, and build a messenger interface. This client can also be used to store passwords using a master key. Anyone who knows the MAC can decrypt the message, so it is your job to keep the MAC safe.

#### How does it work?
CytoSec uses Base64, Rijndael 256, and MD5 encryption to build a string that contains the salt and encrypted message. To further secure the message you can also use a MAC key. This is a passphrase that is used to encrypt the message and only those who have this key can read the message without it being garbage.

It is also possible to bruteforce this MAC, but let's not take that into accaunt just yet.

#### Why did I make this?
I wanted to create this, so I did.

#### How NOT to use this?
Please don't use this for anything illigal, I just made this as an experiment and don't want my hand in termonuclear war.

## TODO
- [x] make a base64 function in C (I actually created an qwezety function... it's base64 on acid)
- [ ] figure out how Rijndael 256 works
- [ ] make a Rijndael 256 function in C
- [ ] implement MD5 in C
- [ ] rewrite the PHP stuffs in C
- [ ] create a cool interface
- [ ] test
- [ ] ???
- [ ] profit
