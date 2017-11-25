# yuncms-note

仿 https://pastebin.com/ 的笔记

[![Latest Stable Version](https://poser.pugx.org/yuncms/yuncms-note/v/stable.png)](https://packagist.org/packages/yuncms/yuncms-note)
[![Total Downloads](https://poser.pugx.org/yuncms/yuncms-note/downloads.png)](https://packagist.org/packages/yuncms/yuncms-note)
[![Build Status](https://img.shields.io/travis/yiisoft/yuncms-note.svg)](http://travis-ci.org/yuncms/yyuncms-note)
[![License](https://poser.pugx.org/yuncms/yuncms-note/license.svg)](https://packagist.org/packages/yuncms/yuncms-note)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require yuncms/yuncms-note
```

or add

```
"yuncms/yuncms-note": "~2.0.0"
```

to the `require` section of your `composer.json` file.

笔记Url规则

```php
    'notes/<page:\d+>' => 'note/note/index',
    'notes/create' => 'note/note/create',
    'notes' => 'note/note/index',
    'notes/<uuid:[\w+]+>/print' => 'note/note/print',
    'notes/<uuid:[\w+]+>/download' => 'note/note/download',
    'note/<uuid:[\w+]+>' => 'note/note/view',

```
    
## License

This is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details.