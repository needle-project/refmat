[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/needle-project/refmat/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/needle-project/refmat/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/needle-project/refmat/badges/build.png?b=master)](https://scrutinizer-ci.com/g/needle-project/refmat/build-status/master)

# 1 Reference Matcher Lib
Use for replacement of reference tokens for nested arrays.

Example:
```yml
foo:
    bar:
        qux: "[[foo.bar.baz.qux]]"
        baz:
            qux: lorem ipsum
```
get's converted in
```yml
foo:
    bar:
        qux: lorem ipsum
        baz:
            qux: lorem ipsum
```

### Note
The project is still under development.
