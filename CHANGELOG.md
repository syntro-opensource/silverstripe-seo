# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

<a name="unreleased"></a>
## [Unreleased]


<a name="6.0.0"></a>
## [6.0.0] - 2025-10-20
### 🍰 Added
- SS6 support ([#72](https://github.com/syntro-opensource/silverstripe-seo/issues/72))


<a name="2.2.4"></a>
## [2.2.4] - 2024-08-07
### 🐞 Fixed
- breadcrumb-generation handles non-Page elements correctly


<a name="2.2.3"></a>
## [2.2.3] - 2024-03-10
### 🐞 Fixed
- Schema graph correctly renders DataObjects as Pages ([#69](https://github.com/syntro-opensource/silverstripe-seo/issues/69))


<a name="2.2.2"></a>
## [2.2.2] - 2024-03-08
### 🐞 Fixed
- robot tag actually assumes free pass for DataObjects


<a name="2.2.1"></a>
## [2.2.1] - 2022-09-18
### 🍰 Added
- standardized module testsuites ([#24](https://github.com/syntro-opensource/silverstripe-seo/issues/24))

### 🐞 Fixed
- Form template is correctly named

### 🧬 Dependencies
- Bump terser from 5.14.1 to 5.14.2 ([#23](https://github.com/syntro-opensource/silverstripe-seo/issues/23))
- Bump [@syntro](https://github.com/syntro)-opensource/webpack-config from 1.3.1 to 1.3.2 ([#22](https://github.com/syntro-opensource/silverstripe-seo/issues/22))


<a name="2.2.0"></a>
## [2.2.0] - 2022-07-19
### 🍰 Added
- Structured data for searchengines ([#17](https://github.com/syntro-opensource/silverstripe-seo/issues/17))
- A `robots` meta-tag automatically generated ([#16](https://github.com/syntro-opensource/silverstripe-seo/issues/16))

### 🐞 Fixed
- Errorpages do not show the seo-fields
- SEO information is not shown on RedirectorPage and VirtualPage ([#19](https://github.com/syntro-opensource/silverstripe-seo/issues/19))
- deprecation warning in php > 8

### 🔧 Changed
- Page analysis is now done via a react field ([#20](https://github.com/syntro-opensource/silverstripe-seo/issues/20))
- update test workflow & dependabot config ([#18](https://github.com/syntro-opensource/silverstripe-seo/issues/18))


<a name="2.1.1"></a>
## [2.1.1] - 2022-03-14
### 🐞 Fixed
- dom reads from stage using correct session ([#15](https://github.com/syntro-opensource/silverstripe-seo/issues/15))


<a name="2.1.0"></a>
## [2.1.0] - 2022-02-11
### 🔧 Changed
- Content is loaded via curl to avoid tampering with session


<a name="2.0.3"></a>
## [2.0.3] - 2021-11-30
### 🐞 Fixed
- content analysis actually uses content of the desired page ([#13](https://github.com/syntro-opensource/silverstripe-seo/issues/13))


<a name="2.0.2"></a>
## [2.0.2] - 2021-09-30
### 🐞 Fixed
- allow overwriting of the Title field via customised data


<a name="2.0.1"></a>
## [2.0.1] - 2021-09-28
### 🐞 Fixed
- body can contain no paragraphs

### 🔧 Changed
- show github actions as testset in readme


<a name="2.0.0"></a>
## [2.0.0] - 2021-09-24
### 🍰 Added
- automated changelog script

### 🔧 Changed
- update readme for v2
- this module only provides SEO analysis (check PR for details: [#10](https://github.com/syntro-opensource/silverstripe-seo/issues/10))


<a name="1.0.3"></a>
## [1.0.3] - 2020-11-02
### 🐞 Fixed
- blog page viewable in cms again


<a name="1.0.2"></a>
## [1.0.2] - 2020-10-25
### 🍰 Added
- chglog config

### 🐞 Fixed
- do not load Meta & SEO fields on non-pages


<a name="1.0.1"></a>
## [1.0.1] - 2020-09-07

<a name="1.0.0"></a>
## [1.0.0] - 2020-09-07

<a name="0.1.0"></a>
## 0.1.0 - 2020-05-25

[Unreleased]: https://github.com/syntro-opensource/silverstripe-seo/compare/6.0.0...HEAD
[6.0.0]: https://github.com/syntro-opensource/silverstripe-seo/compare/2.2.4...6.0.0
[2.2.4]: https://github.com/syntro-opensource/silverstripe-seo/compare/2.2.3...2.2.4
[2.2.3]: https://github.com/syntro-opensource/silverstripe-seo/compare/2.2.2...2.2.3
[2.2.2]: https://github.com/syntro-opensource/silverstripe-seo/compare/2.2.1...2.2.2
[2.2.1]: https://github.com/syntro-opensource/silverstripe-seo/compare/2.2.0...2.2.1
[2.2.0]: https://github.com/syntro-opensource/silverstripe-seo/compare/2.1.1...2.2.0
[2.1.1]: https://github.com/syntro-opensource/silverstripe-seo/compare/2.1.0...2.1.1
[2.1.0]: https://github.com/syntro-opensource/silverstripe-seo/compare/2.0.3...2.1.0
[2.0.3]: https://github.com/syntro-opensource/silverstripe-seo/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/syntro-opensource/silverstripe-seo/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/syntro-opensource/silverstripe-seo/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/syntro-opensource/silverstripe-seo/compare/1.0.3...2.0.0
[1.0.3]: https://github.com/syntro-opensource/silverstripe-seo/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/syntro-opensource/silverstripe-seo/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/syntro-opensource/silverstripe-seo/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/syntro-opensource/silverstripe-seo/compare/0.1.0...1.0.0
