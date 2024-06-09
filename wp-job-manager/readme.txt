
## 2.3.0 - 2024-04-26
New!

* Job Statistics — enable insights like job listing page views, unique visits and search impressions to be collected and displayed to employers in the jobs dashboard.
* Add Google reCAPTCHA v3 support

Improvements:

* New: Job statistics overlay
* Change: Redesign job dashboard
* Change: Allow job duplication in the job dashboard for any job
* Security: Don't return unpublished jobs only in the promote job endpoint
* Fix renewals for WordPress.com licenses
* Fix issues with rich e-mails on some e-mail providers
* Fix e-mail styling in some e-mail clients
* Fix expiry date not showing up in backend editor
* Fix: Add fallback to date format in case it's missing
* Fix: Prevent past dates from being used in the datepicker

For developers:

* Add filter to disable promoted jobs
* Add placeholder options to select field
* Job dashboard template has been rewritten

## 2.2.2 - 2024-02-15
* Fix issue with rich e-mails on some e-mail providers (#2753)
* Fix: 'featured_first' argument now works when 'show_filters' is set to false.
* Improve checkbox and radio inputs for styled forms

## 2.2.1 - 2024-01-31
* Fix PHP 7.x error for mixed returned type (#2726)

## 2.2.0 - 2024-01-29
New:

* Allow scheduling listings during job submission — add an option to show a 'Scheduled Date' field in the job submission form
* Add new [jobs] shortcode parameter, featured_first so you can ensure featured listings always show up on top.
* Add support for user sessions without a full account (used in the Job Alerts extension)

Changes:

* Improve styling for rich text e-mails
* Include plain text alternative for rich text e-mails for better compatibility
* Store previous license when plugin is deactivated for easier reactivation later.
* Update design for settings and marketplace pages

Fixes:

* Fix custom role permission issues (#2673)
* Fix RSS, Reset, Add Alert links not showing on search page without a keyword
* Improve PHP 8 support
* Fix numeric settings field issues
* Improve e-mail formatting and encoding, remove extra whitespace
* Add file type validation and error message to company logo upload
* Fix cache issue when marking jobs as filled/not filled via bulk actions
* Do not emit warning when user with insufficient access to Job Manager menu tries to access wp-admin

## 2.1.1 - 2023-11-21
* Fix link to extensions page (#2650)
* Update Twitter to the new X logo

## 2.1.0 - 2023-11-17
* Fix: Remove public update endpoint and add nonce check (#2642)

## 2.0.0 - 2023-11-17
* Enhancement: Improve settings descriptions (#2639)
* Enhancement: Add directApply in Google job schema (#2635)
* Enhancement: Add 'Don't show this again' link to dismiss promote job modal in the editor (#2632)
* Enhancement: Add landing pages for Applications and Resumes extensions (#2621)
* Fix: Align actions in notices in the center (#2637)
* Fix: Safeguard array in WP_Job_Manager_Settings::input_capabilities (#2631)
* Fix: Escape menu titles and various admin labels (#2630)
* Fix: Incorrectly duplicated string in settings (#2628)
* Fix: Add array initialization to avoid warning (#2619)
* Fix: Do not check for plugin updates when there are no plugins (#2605)
* Change: Reorganize administration menu (#2621)
* Change: Update naming from Add-ons to Extensions, Marketplace (#2621)
