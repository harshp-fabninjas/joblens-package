# Fabninjas/JobLens

JobLens is a Laravel package that helps you track slow queue jobs and show analytics for every job in `queue:work` command it self.

---

## Installation

Install the package via Composer:

```bash
composer require fabninjas/job-lens
```

---

## Publish Files

Publish the migration and config files:

```bash
php artisan vendor:publish --tag=joblens-migrations
php artisan vendor:publish --tag=joblens-config
```

Then run migration:

```bash
php artisan migrate
```

---

## Usage

### Detect Slow Jobs From View

```bash
http://your_base_app_url/slowjobs
```

### Detect Slow Jobs Command

```bash
php artisan joblens:slowjobs
```

---

## Start Queue Worker

Run queue worker to see analytics:

```bash
php artisan queue:work
```

---

## How it works

* If a job has a **timeout defined**, JobLens considers it slow when it reaches **80% of that timeout**
* If no timeout is defined, it uses a default value from config

---

## Configuration

You can change settings in:

```
config/joblens.php
```

Example:

```php
return [

    // If job has timeout mentioned, then slow job threshold will be 80% of the timeout
    // Otherwise it will use the value below
    'slow_job_threshold' => 2, // seconds

    // Track external API calls during job execution
    'track_external_api' => true,

];
```

---

## Features

* Detect slow queue jobs
* Auto calculate threshold based on timeout
* Track external API calls
* Simple Artisan command
* Easy integration

---

## License

MIT License
