# PC-Garage
E-Commerce

#Pages to be done. Needed to be linked as well. **user and visitor

1. Home Page  --->                                      #LINK TO: 2, 3, 4, 5. 8
2. Custom Build  --->                                   **Has 2 pages**
3. Cart  --->                                           **ACCESSIBLE FOR USER ONLY
4. Products/Main Shop  --->                             #LINK TO: 2, 3, 5, 8
5. About  --->                                          #LINK TO: 1, 2 ,3, 4, 7, 8
6. Buy and Sell Prod/Trade in  --->                     **ACCESSIBLE FOR USER ONLY
7. Shopping Filtered page  --->                         #LINK TO: 1, 2, 3, 4, 5, 8
8. Log in & Sign-up  --->                               #LINK TO: 1, 3, 4
9. Profile  --->                                        #LINK TO: ALL
10. Profile settings  --->                              #LINK TO: 9
11. Check out  --->                                     #LINK TO: 7
12. Track your order  --->                              #LINK TO: 9

GLOBAL NAV REQ:
- Every public page must include a shared header/footer linking to: Home, Shop, Custom Build, About, Login/Sign-up, and Cart.
- Protected pages (Cart, Buy/Sell, Profile, Checkout, Order Tracking) require user authentication.

## Technical Requirements
- File Naming: Use descriptive, lower-case HTML filenames (e.g., index.html, shop.html, cart.php).
- Styling: Use a single unified stylesheet named `stylesheet.css`.
- Code Quality: Properly document and comment all HTML structure and CSS styles.
- Use JS(JavaScript) if necessary(file name depends on what page it is used).
- For Image, use image link, don't download the image and upload it to the repository. 


## To do:
Add description for each product.

## Suggestions:
- if yall wanted to edit the layout nor the UI kindly do it, and always FOLLOW the GLOBAL NAV REQ

## Progress
CSS:
stylesheet.css
PAGES:
1. home.php - done already connected to Product
2. product.php - done, linked to home.php
3. cart.php - done, connected to stylesheet.css and navigation
5. about.php - done, connected to stylesheet.css and navigation
6. login.php - done, includes log in and sign-up sections, connected to navigation
## 10/20/2026
7. added cart.js done
8. fixed nav bar to hamburger button done
9. deliver.html - done
## 10/27/2026
10. profile.html - User dashboard linking to profile settings, shopping cart, order tracking, and trade-in hub.
11. profile-settings.html - Account management page for editing user details, shipping address, and password.
12. updated cart.php - with delete buttons & tells the user if they don't have anything in their cart
13. removed images folder
## 11/06/2026 (Aytona)
14. cart.php - fixed and connected to cart.js done
15. checkout.php - connected to checkout.js done
16. cart.htm - connected to checkout done
17. custom.html - added and connected to its own js.
## 11/10/2026 (Gundran)
18. auth-guard.js: Route protection script that checks for isLoggedIn before allowing access to user pages.
19. buy-sell.html: Buy, sell, and hardware trade-in appraisal page with active listing tracker.
20. shop-filtered.html: Filtered shop interface with price range slider, category checkboxes, brand filters, and sorting dropdowns.
21. custom-summary.html: Page 2 of the Custom Build experience, detailing hardware compatibility, estimated wattage, and itemized specs.
22. Standardized currency formatting across all pages to PHP (`₱`).
23. Fixed broken footer link routes (`custom-build.html` → `custom.html`).
24. Standardized <details class="menu-dropdown"> hamburger header navigation across all 13 HTML pages to fulfill global navigation requirements.
25. Repaired broken anchor routes and CTA buttons in home.php to point directly to custom.html.
26. Cleaned up stray character encoding tags in product.php footer and repaired broken footer links in checkout.php.
27. Fixed malformed nested DOM structures and added global deleteItem() function in custom.js to eliminate runtime script errors.
28. Refactored checkout.js to support dynamic order summary recalculation alongside the undo-toast item deletion system.
29. Standardized hardware appraisal values and active trade request history in buy-sell.html to PHP (₱).
30. Added detailed product descriptions for all item cards across product.php and shop-filtered.html.
31. Created cart-manager.js: Centralized localStorage cart manager utility with currency formatting and state event dispatchers.
32. Connected cart.php and cart.js to render item list dynamically from CartManager.getCart().
33. Refactored checkout.js to calculate live order total (#checkout-total-display), clear cart state on submit, and redirect to deliver.html.
34. Connected product.php "Add to cart" button to CartManager.addItem() with user feedback alert.
35. Connected custom-summary.html "Add Entire Rig to Cart" action to serialize hardware rig array into CartManager before navigating to cart.php.
## 11/24/2026 (Tenorio)
36. BUG-001 – Fixed login authentication bypass in `login.php` by removing automatic login state persistence and connecting credential validation to `login.php`.
37. BUG-001 – Improved login error handling to keep unregistered users on the login page and display a sign-up instruction instead of redirecting to a separate PHP response page.
38. BUG-002 – Connected the Sign Up form in `login.php` to `register.php`, adding input validation, duplicate email checking, password hashing, and database registration feedback.
39. BUG-001 – Added custom inline validation messages for empty login fields and invalid email formats, replacing the browser's default warning popups.
40. Converted `cart.html` to `cart.php` while preserving the existing shopping cart interface and functionality.
41. Converted `product.html` to `product.php` while preserving the product catalog and Add to Cart functionality.
42. Updated navigation links and redirects to support the converted PHP pages.
43. Converted the remaining PC-Garage HTML pages into PHP files while preserving the existing layouts and functionality.
44. Restructured the login system by separating the login interface (`login.php`) from its authentication backend (`login-handler.php`).
45. Updated navigation links and JavaScript redirects to reference PHP pages, resolving 404 Not Found errors caused by outdated HTML paths.
46. Fixed outdated `.html` navigation links in `home.php`, `category.php`, and `about.php` by updating them to their corresponding `.php` pages. Corrected the Custom Gaming PCs link to `custom.php` to prevent 404 Not Found errors.
47. BUG-003 – Fixed login session mismatch by updating `auth-guard.js` to validate authenticated PHP sessions through the new `session-check.php` endpoint instead of relying on browser local storage. Restored access to protected pages after successful login.
48. BUG-004 – Fixed user profile information not saving to MySQL by connecting `profile.php` and `profile-settings.php` to the authenticated user's database record. Implemented persistent updates for personal details, shipping information, and password changes with current-password verification, secure hashing, and input validation.
49. BUG-005 – Implemented database-backed hardware appraisal submissions using `trade-submit.php` and `trade-preview.php`. Added dynamic trade history, seller-specific access protection, and a hardware preview displaying submitted details, estimated value, and appraisal status.
50. BUG-006 – Fixed Products navigation across 13 PHP pages by redirecting catalog links to `category.php`, preventing "Product not found" errors caused by missing product IDs. Updated `product.php` to preserve individual product details, corrected Filtered Shop navigation, and improved Profile link visibility while maintaining existing PHP and MySQL functionality.
51. BUG-007 - Implemented a database-backed shopping cart system by creating `cart-handler.php` and updating `cart-manager.js` and `product.php`. Connected the Add to Cart functionality to MySQL using actual product IDs, authenticated PHP sessions, and CSRF protection. Successfully verified that products are saved in the `carts` and `cart_items` tables.
52. BUG-008 - Removed hardcoded shopping cart products and replaced them with dynamically retrieved MySQL records. Updated `cart.php` and `cart.js` to display actual product names, prices, quantities, and calculated totals while preserving the existing PC-Garage shopping cart design.
53. BUG-009 - Fixed shopping cart quantity updates and product removal by connecting the cart interface to the database-backed `cart-handler.php`. Verified that changing product quantities and removing items correctly updates MySQL records without affecting other products. Successfully tested persistent cart operations using the ASUS ROG STRIX SCAR 18 and RTX 5090 Astral.
54. BUG-010 - Part 2 of the MySQL Cart and Checkout Integration. Implemented a database-backed Cash on Delivery (COD) checkout system by connecting `checkout.php`, `checkout.js`, and `checkout-submit.php` to MySQL. Added secure order processing with authenticated PHP sessions, CSRF validation, database transactions, stock validation and deduction, automatic cart clearing, and customer shipping information storage using the `orders`, `order_items`, and `order_shipping_details` tables. Implemented unique checkout request identifiers to prevent duplicate orders and created `checkout-status.php` for read-only order recovery. Added session-based pending checkout tracking, a Check Previous Order feature, and improved shipping and payment selection indicators. Successfully tested order creation, inventory updates, cart clearing, duplicate-request recognition, and checkout recovery. Created `migrations/bug013_checkout_schema.sql` to reproduce the required database changes. Currently supports Cash on Delivery (COD) only; GCash/Maya, debit/credit cards, and PCGarage Wallet are reserved for future integration. Automatic checkout retries are disabled, and unresolved checkout attempts require manual verification.