## About
Typed entity generator from database. It can generate entities for whole database, table/view and from query

## Installation

    $ composer require dodo-it/entity-generator



## For Nette users:
 register extension in your `config.neon`:

```yaml
extensions:
    entityGenerator: DodoIt\EntityGenerator\DI\EntityGeneratorExtension
```

configuration, these are defaults, so you need to provide it only if this doesn't suit you:
```yaml
entityGenerator:
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
        '\DateTime':
            - date
            - datetime
            - timestamp
        '\DateInterval':
            - time
    replacements:
      #table: entityName
    prefix: ''
    sufix: 'Entity'
    extends: '\DodoIt\EntityGenerator\Entity'
    gettersAndSetters: true
    propertyVisibility: protected
```
## USAGE:
run php bin/console entity


sample usage for generated entities with dibi:

    //generates all entities from tables and views in database 
    console entity:generate
    
    //generate entity for table users
    console entity:generate users
    
    //generate entity  UserWithAddress from query
    console entity:generate UserWithAddress --query="SELECT u.*, a.street FROM users u LEFT JOIN addresses a ON a.user_id = u.id"
 
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

You can also add your own methods to entities and change getter/setter functions, they won't be overriden when regenerated
