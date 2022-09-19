<h1 align="center">Indexnow Plugin for <a href="https://awilum.github.io/flextype">Flextype</a></h1>

<p align="center">
<img src="https://img.shields.io/badge/license-MIT-blue.svg?label=License" alt="License MIT"> <img alt="GitHub Repo stars" src="https://img.shields.io/github/stars/flextype-plugins/indexnow?label=Stars"> <img alt="GitHub forks" src="https://img.shields.io/github/forks/flextype-plugins/indexnow?label=Forks"> <a href="https://hitsofcode.com"><img alt="Hits of Code" src="https://hitsofcode.com/github/flextype-plugins/indexnow?branch=1.x"></a>
</p>

Indexnow is a small Flextype plugin for quickly notifying search engines using indexnow protocol whenever their website content is changed.

## Dependencies

The following dependencies need to be downloaded and installed for Indexnow Plugin.

| Item | Version | Download |
|---|---|---|
| [flextype](https://github.com/flextype/flextype) | ^1.0.0-alpha.2 | [download](https://github.com/flextype/flextype/releases) |

## Installation

1. Download & Install all required dependencies.
2. Create new folder `project/plugins/indexnow`.
3. Download [Indexnow Plugin](https://github.com/flextype-plugins/indexnow/releases) and unzip plugin content to the folder `project/plugins/indexnow`.
4. Generate api token with help of the following console command:
```
bin/flextype token:generate
```
5. Create a new %API_TOKEN%.txt file in the website root directory with content %API_TOKEN%

> %API_TOKEN% - generated api token

## Resources
* [Documentation](https://awilum.github.io/flextype/downloads/extend/plugins/indexnow)

## License
[The MIT License (MIT)](https://github.com/flextype-plugins/indexnow/blob/master/LICENSE.txt)
Copyright (c) [Sergey Romanenko](https://github.com/Awilum)