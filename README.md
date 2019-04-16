# IGC File Reader

Read Igc File

## Installation 
```
composer require nemundo/igc
```

### Dev Installation
```
git submodule add https://github.com/nemundo/igc.git lib/igc
```

```
$lib = new Library($autoload);
$lib->source = __DIR__ . '/lib/igc/src/';
$lib->namespace = 'Nemundo\\Igc';
```