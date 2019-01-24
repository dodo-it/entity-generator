
## Installation

    $ composer require dodo-it/dibi-entity

 register extension in your `config.neon`:

```yaml
extensions:
    dibiEntity: DodoIt\DibiEntity\DI\DibiEntityExtension
```

configuration, these are defaults, so you need to provide it only if this doesn't suit you:
```yaml
dibiEntity:
    path: %appDir%/Models/Entities
    namespace: App\Models\Entities
    typeMapping:
        int:
            - int
            - bigint
            - mediumint
            - smallint
        float:
            - decimal
            - float
        bool:
            - bit
            - tinyint
        '\Dibi\DateTime':
            - date
            - datetime
            - timestamp
        '\DateInterval':
            - time

```

sample usage:


    $this->db->select('*')->from('users')->where('id = %i', $id)
				->execute()
				->setRowClass(User::class)
				->fetch();
