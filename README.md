
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

select:

    $this->db->select('*')->from('users')->where('id = %i', $id)
				->execute()
				->setRowClass(User::class)
				->fetch();
update/insert:

	$user = new User();
	$user->setUsername('user1');
	$user->setColumn2(NULL);
	$user->setColumn3(5);
	$this->db->update('users', $user->_getModifications())->where('id = %i', 22);
	// UPDATE users SET ´username´ = 'user1', column_2 = 5, column_3 = NULL WHERE id = 22
