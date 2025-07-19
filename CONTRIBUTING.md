# Contributing to AntiSpoof

Welcome, Adventurer 🛡️  
Thanks for considering a contribution to **AntiSpoof** — a Laravel package built to sniff out IP spoofers and shady agents like a digital bloodhound. 🐕

This document lays out how to contribute code, report issues, suggest features, and level up the package for everyone.

---

## 📋 Table of Contents

- [📋 Table of Contents](#-table-of-contents)
- [🧘 Code of Conduct](#-code-of-conduct)
- [🚀 Getting Started](#-getting-started)
- [🧼 Before You Commit](#-before-you-commit)
- [📬 How to Submit a PR](#-how-to-submit-a-pr)
- [💡 Feature Requests](#-feature-requests)
- [🐛 Found a Bug?](#-found-a-bug)
- [🔐 Security Issues](#-security-issues)
- [🪪 License](#-license)
- [⚔️ Thanks, Warrior](#️-thanks-warrior)

---

## 🧘 Code of Conduct

Please be respectful and constructive.  
We're here to build, learn, and protect — **together**.  
Harassment, trolling, or being a jerk won’t be tolerated.

---

## 🚀 Getting Started

1. **Fork** this repository  
2. **Clone your fork**  
   ```bash
   git clone https://github.com/teikun-86/anti-spoof.git
   cd anti-spoof
   ```

3. **Install dependencies**
   Inside a Laravel app using this package:

   ```bash
   composer install
   ```
4. **Create a feature branch**

   ```bash
   git checkout -b feature/add-awesome-thing
   ```

---

## 🧼 Before You Commit

* Follow **PSR-12** coding style
* Use **4 spaces** for indentation
* Keep methods short and expressive
* Add PHPDoc where it helps understanding
* Run the test suite:

  ```bash
  ./vendor/bin/phpunit
  ```

> ❗ PRs that break existing tests will be denied by the gatekeeper dragon 🐉

---

## 📬 How to Submit a PR

1. Push your branch:

   ```bash
   git push origin feature/add-awesome-thing
   ```
2. Go to the original repo and click **"New Pull Request"**
3. Fill in a clear **title** and **description**
4. Reference any related issues like so:

   ```
   Closes #42
   ```
5. Submit, and we’ll review ASAP!

---

## 💡 Feature Requests

Got an idea to improve detection, reporting, or integration?
Open an [issue](https://github.com/teikun-86/anti-spoof/issues) or submit a PR directly with a clear summary and use case.

---

## 🐛 Found a Bug?

Please include:

* Laravel version
* Package version
* A minimal reproducible example
* Screenshot or request headers if spoofing detection failed

And submit it to the [Issues page](https://github.com/teikun-86/anti-spoof/issues).

---

## 🔐 Security Issues

**Do NOT** file security issues as public GitHub issues.

Instead, email the maintainer directly at:

```
azizfebriyanto12@gmail1.com
```

or use GitHub's private vulnerability reporting (if available).

---

## 🪪 License

This package is open-sourced under the [MIT license](LICENSE.md).
You’re free to remix, expand, and plug it into your fortress 🏰

---

## ⚔️ Thanks, Warrior

Every PR, issue, and suggestion makes this package more powerful.
You’re part of the shield wall now — welcome aboard. ⚔️🛡️

—
Made with ❤️, caffeine, and paranoia
