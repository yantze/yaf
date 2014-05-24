:
# testgroup.sh - Launch PHPUnit for specific test group(s).
#
# Usage: testgroup.sh [ -h <html-dir> ] [ -c <clover-xml-file> ]
#     [ ALL | <test-group> [ <test-group> ... ] ]
#
# This script makes it easier to execute PHPUnit test runs from the
# shell, using @group tags defined in the test suite files to run
# subsets of tests.
#
# To get a list of all @group tags:
#     phpunit --list-groups AllTests.php
#
#

: ${PHPUNIT:="phpunit"}
: ${PHPUNIT_OPTS:="--verbose --colors "}
: ${PHPUNIT_GROUPS:=}
: ${PHPUNIT_BOOTSTRAP:=bootstrap.php}

while [ -n "$1" ] ; do
  case "$1" in 
    -h|--html)
      PHPUNIT_COVERAGE="--coverage-html $2" 
      shift 2 ;;

    -c|--clover)
      PHPUNIT_COVERAGE="--coverage-clover $2" 
      shift 2 ;;

    config)
      PHPUNIT_GROUPS="${PHPUNIT_GROUPS:+"$PHPUNIT_GROUPS,"}config" 
     break ;;

    ALL|all|MAX|max)
     PHPUNIT_GROUPS="" 
     break ;;

    yaf)
     PHPUNIT_BOOTSTRAP="bootstrap_yaf_so.php" 
     shift ;;

    *)
     PHPUNIT_GROUPS="${PHPUNIT_GROUPS:+"$PHPUNIT_GROUPS,"}$1" 
     shift ;;
  esac
done

set -x
${PHPUNIT} ${PHPUNIT_OPTS} ${PHPUNIT_COVERAGE} ${PHPUNIT_DB} \
  ${PHPUNIT_GROUPS:+--group $PHPUNIT_GROUPS} \
  ${PHPUNIT_BOOTSTRAP:+--bootstrap $PHPUNIT_BOOTSTRAP} framework
