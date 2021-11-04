# Blua Blue
[![Maintainability](https://img.shields.io/badge/built%20with-neoan3%20cli-blue.svg?style=flat&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAACVklEQVRYR+2VT0iTYRjAf990i7nNNs1viyiT/SkPEbKioMQygrIOYSpE1w51sA4RhAhegg5Rp87dghAWFURBVxUFbUZEpW1LTdwm+K/Npdv8OqzZt3dzrg56+X7H53lenh/P8/K+0qHmowrbiE4MbDWagCagCWgCJQnIrmUxVBCjNUn7o6803ZjE7o6L6YIUFZDdcVq6gjTfmhBTeeh3pDnfFaJq7wpTHyycvjlJS1dwU/lyMQBg98Txtocxy0n8PjvjfVaxJBedwtnbE9TU/eLT2yqCAzaCg1Y8jfOc6pwkFtUz3OsgOm4STyKpPyO7J463I0yFLYX/uUyg3wpIqvLCNF2f4uCZeaIBIy+7naylVIOVFJwnFvC2RonNlTPS6yAy9ldkfQIXugMYK9OM+GRCQzsppXEGBYtjhZWYjncPa3ObAygSgT4bgT4rdccWabw2TWKpjNf3nIB6BTpYA5R/+Jyr9iUwVScZeLIHc3WS2KxBLMlBUTI91DcvbwVHOsIYbSne+2SCAxuvwGBK0fZgDEtNitEXNQw93S2W/CGzgobWKIn5coaFFeQIZMneBfOuFH6fzHi/FdbUIgrn7n6n1vuTmS9GXvW4hDygU3CfXKDhcoTYrD5v91kKCmSR3ZmJVDpWedZZvx4/fCnC8asREks6fHc8xOfyR3/l8WcWZwwb3v4sRQWyyK5lot8qAHDUx7nYE0CS4M39/fwYrRSqM6jPFKMkATVlhjSOA8sYTGlCg5u8DyVQ8CEqRnq1jOmPFjH83xR9ircCTUAT0AQ0gW0X+A3UX84kMp7NmAAAAABJRU5ErkJggg==&colorA=2d4235)](https://github.com/neoan3/cli#neoan3-cli)

Open-source version of blua.blue

## Table of Contents

* [About the Project](#about-the-project)
  * [Built With](#built-with)
* [Getting Started](#getting-started)
  * [Prerequisites](#prerequisites)
  * [Installation](#installation)
* [Usage](#usage)
* [Contributing](#contributing)
* [License](#license)
* [Contact](#contact)

## About the Project

This complete rewrite of blua.blue is the result new neoan3 technologies coming out. 
The goal of this rewrite was to utilize the speed-advantage of vastN3 technology while maintaining the possibility for a headless approach.

Enjoy...

### Built With

- [neoan3](https://neoan3.rocks)
- [Gaudiamus](https://gaudiamus-css.github.io/)
- [vastN3](https://github.com/vast-n3)

## Getting Started

### Prerequisites

- neoan3-cli
- PHP8
- Composer
- GIT
- MySQL, MariaDB or compatible flavor
- mailjet account (other integrations will follow)
- (for styling: node + a node package manager)

### Installation

You can fork or clone this repository, or use the composer create-project command.
Once in your project, start with the setup:

1. database
   
   run `neoan3 new database blua_blue` and follow the instructions, naming the credentials "blua_blue_db" when prompted.

2. JWT-secret
   
   run `neoan3 credentials` and select "new credentials". Then, name them "blua_stateless" and fill out the template with a string of your choice.
3. Mailjet

   run `neoan3 credentials` and select "new credentials". Then, name them "blua_mailjet" and fill out the credential-template with your private and public API key.

_NOTE_: If you have permission issues, either change permissions on your file-system for 
/credentials/credentials.json or change the cli's default credential-location 
with `neoan3 set credential-path /my/path`. DO NOT SET THE PATH TO WITHIN THE WEB-ROOT

## Usage

You can test & develop in multiple ways. The easiest way is using the cli-tool: `neoan3 develop`.
When deploying, please take the following measures:
- exclude the file "vue" and the component "Migrate" when deploying.
- when running on Apache, inspect .htaccess and change as needed. 

## License

Licensing party for the open-source version of blua.blue is corpscrypt LLC, distributed under MIT

All third party references, libraries, logos and APIs are subject to respective licenses.

Beyond license(s) concerning this project, the following tools/libraries hold their respective licenses: 
- neoan3 cli [GNU AFFERO GENERAL PUBLIC License](https://github.com/neoan3/cli/blob/master/LICENSE)
- neoan3 framework [MIT License](https://github.com/sroehrl/neoan3/blob/master/LICENSE)

_MAINTAINER/OWNER: You may not remove above license statement, 
but are free to list your own additional license(s) as needed. Add your license(s) to the file "LICENSE" as needed._

## Contact

https://blua.blue/contact-us

Also, feel free to join the [neoan3 discord server](https://discord.gg/WeyS567)