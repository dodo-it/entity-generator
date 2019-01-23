
## Installation

    $ composer require dodo-it/dibi-entity

 register extension in your `config.neon`:

```yaml
extensions:
    dibiEntity: DodoIt\DibiEntity\DI\DibiEntityExtension
```

configuration:
```yaml
dibiEntity:
    path: %appDir%/Models/Entities
    namespace: App\Models\Entities
```