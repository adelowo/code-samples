
There is a sample console script to use in testing the app out. It has been aptly named `github.php`.

### Usage

#### Fetching a user's profile

```bash

php github.php -u="fabpot"

```

#### Fetching a user's repos

```bash

php github.php -u="fabpot" -r="true"

```


#### Dump the result into a file

By default the result are `var_dump`d to the terminal, but if you'd like to see this in a file, you do this ;

```bash

php github.php -u="fabpot" -r="true" -f="file.json"

```

