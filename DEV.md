
### Submodule Installation
```
git submodule add https://github.com/nemundo/igc.git lib/igc
```

```
$lib = new Library($autoload);
$lib->source = __DIR__ . '/lib/igc/src/';
$lib->namespace = 'Nemundo\\Igc';
```


###Submodule Deinstallation
```
git submodule deinit lib/igc
git rm lib/igc
```