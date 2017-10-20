#!/usr/bin/env bash

RED='\033[1;31m'
GREEN='\033[1;32m'
buildResult=1
buildMessage=""
result=0

function runTests {
    "$TRAVIS_BUILD_DIR/$SHOP_DIR/vendor/bin/console" transfer:generate
    if [ "$?" = 0 ]; then
        buildMessage="${buildMessage}\n${GREEN}Transfer objects generation was successful"
    else
        buildMessage="${buildMessage}\n${RED}Transfer objects generation was not successful"
        result=$((result+1))
    fi

    "$TRAVIS_BUILD_DIR/$SHOP_DIR/vendor/bin/console" propel:install
    if [ "$?" = 0 ]; then
        buildMessage="${buildMessage}\n${GREEN}Propel models generation was successful"
    else
        buildMessage="${buildMessage}\n${RED}Propel models generation was not successful"
        result=$((result+1))
    fi

    echo "Setup for tests..."
    ./setup_test -f

    echo "Running tests..."
    "$TRAVIS_BUILD_DIR/$SHOP_DIR/vendor/bin/codecept" build -c "vendor/spryker-eco/$MODULE_NAME/"
    "$TRAVIS_BUILD_DIR/$SHOP_DIR/vendor/bin/codecept" run -c "vendor/spryker-eco/$MODULE_NAME/"
    if [ "$?" = 0 ]; then
        buildMessage="${buildMessage}\n${GREEN}Tests are passing"
    else
        buildMessage="${buildMessage}\n${RED}Tests are failing"
        result=$((result+1))
    fi
    cd "$TRAVIS_BUILD_DIR/$SHOP_DIR"
    echo "Tests finished"
    return $result
}

function checkArchRules {
    echo "Running Architecture sniffer..."
    errors=`vendor/bin/phpmd "vendor/spryker-eco/$MODULE_NAME/src" text vendor/spryker/architecture-sniffer/src/ruleset.xml --minimumpriority=2`
    errorsCount=`echo "$errors" | wc -l`

    if [[ "$errorsCount" = "0" ]]; then
        buildMessage="$buildMessage\n${GREEN}Architecture sniffer reports no errors"
    else
        echo -e "$errors"
        buildMessage="$buildMessage\n${RED}Architecture sniffer reports $errorsCount error(s)"
    fi
}

function checkCodeSniffRules {
    licenseFile="$TRAVIS_BUILD_DIR/.license"
    if [ -f "$licenseFile" ]; then
        echo "Preparing correct license for code sniffer..."
        cp "$licenseFile" "$TRAVIS_BUILD_DIR/$SHOP_DIR/.license"
    fi

    echo "Running code sniffer..."
    errors=`vendor/bin/console code:sniff "vendor/spryker-eco/$MODULE_NAME/src"`
    errorsCount=$?

    if [[ "$errorsCount" = "0" ]]; then
        buildMessage="$buildMessage\n${GREEN}Code sniffer reports no errors"
    else
        echo -e "$errors"
        buildMessage="$buildMessage\n${RED}Code sniffer reports some error(s)"
    fi
}

function checkPHPStan {
    echo "Installing PHPStan..."
    composer require --dev phpstan/phpstan
    echo "Updating code-completition..."
    vendor/bin/console dev:ide:generate-auto-completion
    echo "Running PHPStan..."
    errors=`php -d memory_limit=2048M vendor/bin/phpstan analyze -c phpstan.neon "vendor/spryker-eco/$MODULE_NAME/src" -l 2`

    errorsCount=`echo "$errors" | wc -l`
    if [[ "$errorsCount" = "0" ]]; then
        buildMessage="$buildMessage\n${GREEN}PHPStan reports no errors"
    else
        echo -e "$errors"
        buildMessage="$buildMessage\n${RED}PHPStan reports some error(s)"
    fi
}

function checkWithLatestDemoShop {
    echo "Checking module with latest Demo Shop..."
    composer config repositories.ecomodule path "$TRAVIS_BUILD_DIR/$MODULE_DIR"
    composer require "spryker-eco/$MODULE_NAME @dev"
    result=$?
    if [ "$result" = 0 ]; then
        buildMessage="${buildMessage}\n${GREEN}$MODULE_NAME is compatible with the modules used in Demo Shop"
        if runTests; then
            buildResult=0
            checkModuleWithLatestVersionOfDemoShop
        fi
    else
        buildMessage="${buildMessage}\n${RED}$MODULE_NAME is not compatible with the modules used in Demo Shop"
        checkModuleWithLatestVersionOfDemoShop
    fi
}

function checkModuleWithLatestVersionOfDemoShop {
    echo "Merging composer.json dependencies..."
    updates=`php "$TRAVIS_BUILD_DIR/$MODULE_DIR/build/merge-composer.php" "$TRAVIS_BUILD_DIR/$MODULE_DIR/composer.json" composer.json "$TRAVIS_BUILD_DIR/$MODULE_DIR/composer.json"`
    if [ "$updates" = "" ]; then
        buildMessage="${buildMessage}\n${GREEN}$MODULE_NAME is compatible with the latest version of modules used in Demo Shop"
        return
    fi
    buildMessage="${buildMessage}\nUpdated dependencies in module to match Demo Shop\n$updates"
    echo "Installing module with updated dependencies..."
    composer require "spryker-eco/$MODULE_NAME @dev"
    result=$?
    if [ "$result" = 0 ]; then
        buildMessage="${buildMessage}\n${GREEN}$MODULE_NAME is compatible with the latest version of modules used in Demo Shop"
        runTests
    else
        buildMessage="${buildMessage}\n${RED}$MODULE_NAME is not compatible with the latest version of modules used in Demo Shop"
    fi
}

updatedFile="$TRAVIS_BUILD_DIR/$SHOP_DIR/vendor/codeception/codeception/src/Codeception/Application.php"
grep APPLICATION_ROOT_DIR "$updatedFile"
if [ $? = 1 ]; then
    echo "define('APPLICATION_ROOT_DIR', '$TRAVIS_BUILD_DIR/$SHOP_DIR');" >> "$updatedFile"
fi

cd $SHOP_DIR
checkWithLatestDemoShop
if [ -d "vendor/spryker-eco/$MODULE_NAME/src" ]; then
    checkArchRules
    checkCodeSniffRules
    checkPHPStan
fi

echo -e "$buildMessage"
exit $buildResult
