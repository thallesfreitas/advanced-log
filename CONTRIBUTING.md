# CONTRIBUTING.md

[Leia em Português](CONTRIBUTING-pt-BR.md)

# Contributing

Thank you for considering contributing to the Advanced Logger! This document provides some basic guidelines to make the contribution process easier and more effective.

## Development Environment Setup

1. Fork the repository
2. Clone your fork:

```bash
git clone https://github.com/your-username/advanced-log.git
```

3. Install dependencies:

```bash
composer install
```

4. Create a branch for your feature or fix:

```bash
git checkout -b feature-name
```

## Testing

We use PHPUnit for testing. To run tests:

```bash
composer test
```

Please ensure that:

- All new features have corresponding tests
- All tests pass before submitting a Pull Request
- Test coverage remains high

## Coding Standards

This project follows the PSR-12 coding standards. To check your code:

```bash
composer check-style
```

To automatically fix style issues:

```bash
composer fix-style
```

## Pull Request Process

1. Update the README.md with details of changes if needed
2. Update the CHANGELOG.md following the Keep a Changelog format
3. Update any documentation that might be affected by your changes
4. Make sure all tests pass
5. Create your Pull Request with a clear title and description

### Pull Request Guidelines

- Use a clear and descriptive title
- Include relevant issue numbers in the description
- Include screenshots or console output if relevant
- Document new code based on the PSR standards
- Update documentation if required

## Creating Issues

When creating issues, please:

- Use a clear and descriptive title
- Provide detailed reproduction steps
- Include system information if relevant
- Attach log files or screenshots if applicable

## Code of Conduct

### Our Pledge

We are committed to providing a friendly, safe, and welcoming environment for all contributors.

### Expected Behavior

- Be respectful and inclusive
- Be collaborative
- Accept constructive criticism gracefully
- Focus on what's best for the community

### Unacceptable Behavior

- Harassment of any kind
- Discriminatory jokes and language
- Violent or threatening language
- Any other conduct which could reasonably be considered inappropriate

## Getting Help

If you need help, you can:

- Create an issue
- Email the maintainers at your@email.com
- Join our Discord community (if available)

---
