alias test-prepare='
    php bin/console doctrine:database:drop --env=test --if-exists --force
    php bin/console doctrine:database:create --env=test --if-not-exists
    php bin/console doctrine:migrations:migrate --env=test -n
'

alias test-run-functional='
     php bin/codecept run Functional
'

alias test-run-unit='
     php bin/codecept run Unit
'

alias test-functional='php bin/codecept run -f -- Functional'
