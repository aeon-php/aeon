## [Unreleased] - 2023-01-25

### Fixed
- [#69](https://github.com/aeon-php/rate-limiter/pull/69) - **Failing tests by updating min required PHP 8.1 to 8.1.10 that resolves internal PHP bug related to handling time units small then a second** - [@norberttech](https://github.com/norberttech)

### Updated
- [ddc897](https://github.com/aeon-php/rate-limiter/commit/ddc8976febcee46756b228147cdcbe3191af555a) - **dependabot-auto-merge.yaml** - [@norberttech](https://github.com/norberttech)
- [6eae3f](https://github.com/aeon-php/rate-limiter/commit/6eae3f40b743a99f84041e3463d93e477208d47f) - **aeon-php/calendar dependency** - [@norberttech](https://github.com/norberttech)
- [8d7084](https://github.com/aeon-php/rate-limiter/commit/8d70848a07ac52cb8de977baac3a1dc414bea758) - **README.md** - [@norberttech](https://github.com/norberttech)
- [0880ac](https://github.com/aeon-php/rate-limiter/commit/0880ac0b254ff124ff918e5223decf93446c8528) - **dependencies** - [@norberttech](https://github.com/norberttech)

## [0.8.0] - 2022-06-19

### Added
- [f02ed7](https://github.com/aeon-php/rate-limiter/commit/f02ed71620ec410ee7459ea3352e8a09c6b1d312) - **php 8.1 to allowed versions** - [@norberttech](https://github.com/norberttech)

### Changed
- [a524ad](https://github.com/aeon-php/rate-limiter/commit/a524adb233b8ecfd2dc30de3c2cc9d07e3d3dd1c) - **custom workflows into aeon-php reusable workflows** - [@norberttech](https://github.com/norberttech)

### Fixed
- [4a8746](https://github.com/aeon-php/rate-limiter/commit/4a874675f2425e9d427b26884fd97eb8b031eacc) - **PHP versions scope** - [@norberttech](https://github.com/norberttech)

### Updated
- [#63](https://github.com/aeon-php/rate-limiter/pull/63) - **Constraints for psr-cache to include v2 & v3** - [@GwendolenLynch](https://github.com/GwendolenLynch)
- [627d72](https://github.com/aeon-php/rate-limiter/commit/627d72b38f7b802e3e905b5211fbcd53756483b6) - **infection** - [@norberttech](https://github.com/norberttech)
- [fd1060](https://github.com/aeon-php/rate-limiter/commit/fd1060d7ae09ec09aa18f8d696d263fc4669a454) - **tools dependencies** - [@norberttech](https://github.com/norberttech)
- [588f6b](https://github.com/aeon-php/rate-limiter/commit/588f6b9d21bd5365c80ca7ef194feb965e3e9490) - **dependencies** - [@norberttech](https://github.com/norberttech)

## [0.7.0] - 2021-02-25

### Fixed
- [#23](https://github.com/aeon-php/rate-limiter/pull/23) - **rounding TTL of cached hit's into the nearest second making milliseconds time windows ignored** - [@DawidSajdak](https://github.com/DawidSajdak)

## [0.6.0] - 2021-02-24

### Fixed
- [#22](https://github.com/aeon-php/rate-limiter/pull/22) - **concurrency issue, when one process is sleeping and other already reduced available quota** - [@DawidSajdak](https://github.com/DawidSajdak)

## [0.5.0] - 2021-01-25

### Added
- [#15](https://github.com/aeon-php/rate-limiter/pull/15) - **aeon-php/automation integration** - [@norberttech](https://github.com/norberttech)

### Changed
- [11b528](https://github.com/aeon-php/rate-limiter/commit/11b5283b8c2347e566595e58b9c367ea744ef449) - **Update CHANGELOG.md** - [@norberttech](https://github.com/norberttech)

### Fixed
- [#15](https://github.com/aeon-php/rate-limiter/pull/15) - **namespaces change in one of the dependencies** - [@norberttech](https://github.com/norberttech)

## [0.4.0] - 2020-12-20

### Changed
- [b2d2e6](https://github.com/aeon-php/rate-limiter/commit/b2d2e61047f2c0f2256e0035a765c88dd1c4fe72) - **Update CHANGELOG.md** - [@norberttech](https://github.com/norberttech)
- [#10](https://github.com/aeon-php/rate-limiter/pull/10) - **tools, moved phpunit to tools** - [@norberttech](https://github.com/norberttech)
- [a53eec](https://github.com/aeon-php/rate-limiter/commit/a53eec4940caba047447181cc9ee98497428581b) - **Update CHANGELOG.md** - [@norberttech](https://github.com/norberttech)
- [#9](https://github.com/aeon-php/rate-limiter/pull/9) - **Updated phpunit from version 9.4 to 9.5** - [@norberttech](https://github.com/norberttech)
- [#9](https://github.com/aeon-php/rate-limiter/pull/9) - **phpunit from composer to phive dependency** - [@norberttech](https://github.com/norberttech)
- [#5](https://github.com/aeon-php/rate-limiter/pull/5) - **Run tests against php8** - [@norberttech](https://github.com/norberttech)

### Removed
- [#9](https://github.com/aeon-php/rate-limiter/pull/9) - **windows from github tests workflow** - [@norberttech](https://github.com/norberttech)

## [0.3.0] - 2020-11-26

### Added
- [#4](https://github.com/aeon-php/rate-limiter/pull/4) - **more details into RateLimitException** - [@norberttech](https://github.com/norberttech)

### Changed
- [034bc8](https://github.com/aeon-php/rate-limiter/commit/034bc8fc4d4bbe2ba2be9847f2ebe106569f9940) - **Update CHANGELOG.md** - [@norberttech](https://github.com/norberttech)

## [0.2.0] - 2020-11-24

### Added
- [#3](https://github.com/aeon-php/rate-limiter/pull/3) - **reset in and initial capacity to the rate limiter API** - [@norberttech](https://github.com/norberttech)

### Changed
- [d2f05e](https://github.com/aeon-php/rate-limiter/commit/d2f05e44226cfde028b708fdd9ab8c172ff95eb4) - **Update CHANGELOG.md** - [@norberttech](https://github.com/norberttech)

## [0.1.0] - 2020-11-22

### Added
- [#1](https://github.com/aeon-php/rate-limiter/pull/1) - **method to get current capacity of rate limiter for given algorithm** - [@norberttech](https://github.com/norberttech)
- [43494c](https://github.com/aeon-php/rate-limiter/commit/43494cf82f7c4dea2c8289385ec70b917ef5efc8) - **link to forum** - [@norberttech](https://github.com/norberttech)
- [f03f34](https://github.com/aeon-php/rate-limiter/commit/f03f343bcefd765ad2c50596c1757de5d6a30e51) - **PSR Cache Storage implementation** - [@norberttech](https://github.com/norberttech)

### Changed
- [3944b3](https://github.com/aeon-php/rate-limiter/commit/3944b3cf1edc3696649a6bf971d9c524fbaa8385) - **mb-string extension with polyfill, added changelog** - [@norberttech](https://github.com/norberttech)
- [9d4acd](https://github.com/aeon-php/rate-limiter/commit/9d4acdc5e5a3be7346e3869e2e144042dea1b377) - **Increased tests coverage** - [@norberttech](https://github.com/norberttech)
- [7260ce](https://github.com/aeon-php/rate-limiter/commit/7260ceaeeda9c099d730119c772b388aa89af475) - **Limit aeon-php/calendar to >=0.11.0** - [@norberttech](https://github.com/norberttech)
- [9ca925](https://github.com/aeon-php/rate-limiter/commit/9ca925c7bafd32de5e1dc750591425f9ae36f392) - **Initial commit** - [@norberttech](https://github.com/norberttech)

Generated by [Automation](https://github.com/aeon-php/automation)