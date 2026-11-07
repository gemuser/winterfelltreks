# Winterfell Treks & Expedition

Premier High-Altitude Mountaineering & Wilderness Expedition Platform

A fully dynamic, CMS-driven Laravel 11 application for Winterfell Treks & Expedition — founded in 2083 BS by Mr. Asmin Bhattarai.

## Features

### Zero Hardcoded Content
- **100% Dynamic**: Every string, image, link, button, and metadata element is stored in the database
- **Atomic CMS**: Granular section-element architecture allows editing any part of any page
- **Visual Page Builder**: Drag-and-drop section management with live preview

### Editorial Alpine-Brutalist Design
- **Typography**: Cinzel Decorative (display) + Space Grotesk (body) — razor-sharp editorial contrast
- **Color Palette**: Deep abyssal slate (#0b0d10), glacial granite (#1a1d24), rescue amber (#e65c00), alpine white (#f4f6f8)
- **Layout**: Topographical grid lines, asymmetric compositions, full-bleed photography masks
- **No AI Slop**: Zero gradients, glassmorphism, pill buttons, card grids, or fake statistics

### Technical Stack
- **Backend**: Laravel 11.x with clean modular architecture
- **Database**: MySQL 8.x with strict foreign keys and JSON payloads
- **Frontend**: Native Blade + Alpine.js + GSAP ScrollTrigger
- **Styling**: Tailwind CSS v4 with custom design tokens
- **Assets**: Vite for bundling, WebP/AVIF image optimization

### Dynamic Page Types
- Homepage with cinematic hero, editorial splits, destination/experience/package grids
- Destinations listing with search/filter/category
- Destination detail pages with highlights, best time, travel info
- Expeditions/packages with itineraries, inclusions/exclusions, gear checklists
- Experiences with categories, locations, activities
- Travel Guide/Blog with categories, search, featured articles
- Gallery with masonry layout, lightbox, categories
- About page with mission/vision/values/team
- Contact page with form, map, info
- Enquiry system with context-aware forms
- Legal pages (Privacy, Terms, Cookie Policy)
- FAQ with categorized accordions
- 404 page with helpful navigation

### Admin Dashboard
- Real-time stats: pages, destinations, expeditions, experiences, blog posts, testimonials, enquiries
- Full page CRUD with SEO/OpenGraph/Schema management
- Visual section builder with 25+ section types
- Element editor for text, rich text, images, links, repeaters, JSON
- Drag-and-drop reordering for pages, sections, elements
- Global site settings organized by groups
- Expedition management with full itinerary editor
- Enquiry management with status workflow
- Responsive design for tablet/mobile admin access

### Enquiry System
- Context-aware forms (auto-populate destination/package/experience)
- Multi-step validation with field-level errors
- Reference number generation (WTE-XXXXX)
- Email notifications to admin and customer
- Status workflow: New → Under Review → Contacted → Quotation Sent → Follow-up → Closed
- Internal notes and staff assignment

### SEO & Performance
- Dynamic meta tags, OpenGraph, Twitter Cards
- Auto-generated JSON-LD structured data (TouristInformationCenter/TravelAgency)
- XML sitemap at `/sitemap.xml` with all published content
- Robots.txt with sitemap reference
- Canonical URLs, proper heading hierarchy
- GSAP animations with hardware acceleration
- WebP/AVIF images with explicit dimensions (zero CLS)

## Installation

```bash
# Clone and install dependencies
cd winterfell
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
# Configure .env with your MySQL credentials
php artisan migrate --seed

# Build assets
npm run build

# Start development server
php artisan serve
npm run dev
```

## Database Schema

### Core Tables
- `site_settings` - Global configuration (key-value with groups/types)
- `pages` - Dynamic pages with SEO, nav/sidebar/footer visibility
- `page_sections` - Modular sections per page (25+ types)
- `section_elements` - Atomic content elements (text, richtext, image, link, repeater, json)

### Content Tables
- `expeditions` - Tour packages with full itineraries, gear, inclusions/exclusions
- `destinations` - Himalayan regions with descriptions, galleries, travel info
- `experiences` - Activities with categories, locations, related packages
- `blog_posts` + `blog_categories` - Travel guide articles
- `gallery_images` + `gallery_categories` - Photography gallery
- `testimonials` - Customer reviews
- `faqs` - Categorized FAQs
- `enquiries` - Customer enquiries with full context
- `users` - Admin users with roles

## Section Types (25+)

| Type | Description |
|------|-------------|
| `hero_cinematic` | Full-screen hero with background, headline, dual CTAs |
| `page_hero` | Standard page header with title/subtitle |
| `editorial_split` | Text + image side-by-side (flexible layouts) |
| `destination_grid` | Destination cards with search/view-all |
| `package_grid` | Expedition cards with price/duration |
| `experience_grid` | Experience cards |
| `feature_grid` | Icon + title + description (Why Choose Us) |
| `gallery_masonry` | Masonry image gallery with lightbox |
| `testimonial_slider` | Auto-playing testimonial carousel |
| `article_grid` | Blog article cards |
| `cta_banner` | Call-to-action section |
| `mission_vision` | Side-by-side mission/vision |
| `values_grid` | Company values grid |
| `team_grid` | Team member cards |
| `contact_info` | Contact details from global settings |
| `enquiry_form` | Context-aware enquiry form |
| `faq_accordion` | Categorized FAQ accordions |
| `legal_content` | Full-width legal text |
| `route_profile` | Elevation chart for routes |
| `elevation_matrix` | Tabular elevation data |
| `text_stream` | Long-form editorial content |
| `destination_listing` | Searchable/filterable destination grid |
| `package_listing` | Searchable/filterable package grid |
| `experience_listing` | Filterable experience grid |
| `google_map` | Embedded Google Maps |

## Element Types

| Type | Use Case |
|------|----------|
| `text` | Plain text (headlines, labels) |
| `richtext` | HTML content (descriptions, body) |
| `image` | Image with alt text |
| `link` | Button/link with text, URL, style |
| `repeater` | Dynamic arrays (cards, lists) |
| `json` | Raw JSON for complex configs |

## Design Tokens

```css
/* Colors */
--color-primary: #0b0d10;        /* Abyssal Slate */
--color-primary-light: #1a1d24;  /* Glacial Granite */
--color-primary-lighter: #2a2f3a; /* Weathered Limestone */
--color-accent: #e65c00;         /* Rescue Amber */
--color-accent-light: #d97706;   /* Amber Hover */
--color-text-primary: #0b0d10;
--color-text-secondary: #4a4f5a;
--color-text-muted: #94a3b8;
--color-background: #ffffff;
--color-background-alt: #f4f6f8;
--color-border: #e2e8f0;

/* Typography */
--font-display: "Cinzel Decorative", "Cormorant Garamond", "Syne", serif;
--font-body: "Space Grotesk", "JetBrains Mono", "Cabinet Grotesk", monospace;

/* Spacing Scale */
4px, 8px, 12px, 16px, 24px, 32px, 48px, 64px, 80px, 96px
```

## Commands

```bash
# Seed database with demo content
php artisan winterfell:seed

# Refresh database and re-seed
php artisan winterfell:refresh

# Display inspiring quote
php artisan inspire
```

## Project Structure

```
app/
├── Http/Controllers/
│   ├── PageController.php          # Public page rendering
│   ├── EnquiryController.php       # Enquiry handling
│   ├── SitemapController.php       # XML sitemap + robots.txt
│   ├── Admin/
│   │   ├── DashboardController.php
│   │   ├── PageController.php      # Page CRUD
│   │   ├── SectionController.php   # Section CRUD
│   │   ├── ElementController.php   # Element CRUD
│   │   ├── SiteSettingController.php
│   │   ├── ExpeditionController.php
│   │   └── EnquiryController.php
├── Models/                         # Eloquent models with relationships
├── View/Components/
│   └── Sections/                   # 25+ Blade section components
resources/
├── views/
│   ├── layouts/app.blade.php       # Main layout with SEO
│   ├── pages/render.blade.php      # Dynamic page renderer
│   ├── sections/                   # Section component templates
│   ├── admin/                      # Admin panel views
│   └── auth/                       # Authentication views
├── css/app.css                     # Tailwind + custom design tokens
└── js/app.js                       # Alpine.js + GSAP + utilities
database/
├── migrations/                     # 11 migrations
└── seeders/                        # 12 seeders with full demo content
```

## Security

- CSRF protection on all forms
- Server-side validation on all inputs
- SQL injection prevention via Eloquent ORM
- XSS prevention via Blade escaping
- Secure password hashing (bcrypt)
- Rate limiting on public forms
- Role-based admin access
- HTTPS enforcement in production

## Accessibility

- Semantic HTML5 structure
- Proper heading hierarchy (h1-h6)
- Keyboard navigation for all interactive elements
- Visible focus indicators
- Descriptive alt text for all images
- Sufficient color contrast (WCAG AA)
- Accessible form labels and validation messages
- Screen-reader-friendly controls
- ARIA attributes where needed

## Responsive Breakpoints

| Breakpoint | Width | Behavior |
|------------|-------|----------|
| Mobile | < 640px | Single column, drawer nav, stacked forms |
| Tablet | 640px - 1024px | 2-column grids, adjusted spacing |
| Desktop | 1024px - 1280px | Full layouts, multi-column grids |
| Wide | > 1280px | Max-width containers, optimal reading width |

## License

Proprietary — Winterfell Treks & Expedition © 2083 BS / 2026 AD