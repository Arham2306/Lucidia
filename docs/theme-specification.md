# Prompt: Build a Custom WordPress Blog Theme

You are an expert WordPress theme developer, UI/UX designer, and frontend engineer.

I want you to build a **fully custom WordPress blog theme from scratch**. Do not use Elementor, Divi, WPBakery, or another page builder. The theme should be lightweight, modern, responsive, SEO-friendly, accessible, and easy to customize.

## 1. Project Goal

Create a professional, editorial-style blog website that can be used for articles, news, guides, tutorials, opinions, and other long-form content.

The design should feel like a modern premium publication rather than a generic WordPress blog.

The theme must be built using standard WordPress theme architecture and WordPress APIs so that it works properly with the WordPress admin dashboard.

## 2. Design Direction

Use a clean, sophisticated, content-first design.

### Visual style

- Modern editorial / magazine aesthetic
- Clean typography
- Excellent readability
- Generous whitespace
- Strong visual hierarchy
- Subtle borders and shadows
- Smooth hover effects
- Minimal but polished animations
- Responsive card layouts
- Large featured images
- Clear category and metadata styling
- Professional desktop and mobile layouts

### Color system

Use a mostly neutral palette:

- Background: white or very light gray
- Primary text: near-black
- Secondary text: muted gray
- Accent: one sophisticated accent color
- Borders: subtle gray

Keep the color system centralized through CSS variables so it can be changed easily.

## 3. Required Pages / Templates

Create all necessary WordPress templates.

At minimum:

- Home page
- Blog/archive page
- Single post page
- Category archive
- Tag archive
- Author archive
- Search results
- 404 page
- Static page
- Date archive
- Comments section
- Header
- Footer

Use the WordPress template hierarchy correctly.

Do not hard-code post content.

## 4. Homepage Structure

Create a polished blog homepage with the following structure:

### Header

Include:

- Site logo / site title
- Primary navigation
- Search button/icon
- Mobile menu button
- Optional CTA button
- Sticky header behavior

The navigation must use WordPress menus rather than hard-coded links.

### Hero / Featured Section

Create a prominent featured article section.

Include:

- Large featured image
- Category
- Post title
- Excerpt
- Author
- Published date
- Reading time
- Read more link

Use the latest post or a configurable featured post.

### Latest Articles

Create a responsive article grid.

Each card should contain:

- Featured image
- Category
- Title
- Short excerpt
- Author
- Date
- Reading time
- Read more link

Use WordPress's `WP_Query` or the appropriate WordPress query system.

### Secondary Content Section

Add a magazine-style section for additional posts.

Example:

- 2-column featured layout
- Smaller article cards
- Popular posts
- Editor's picks

### Newsletter Section

Create a visually distinct newsletter signup section.

The theme should provide the frontend structure only. Do not require a specific email marketing service.

Make the form easy to integrate with services such as Mailchimp, ConvertKit, Brevo, etc.

### Footer

Include:

- Site branding
- Short description
- Navigation/menu
- Social links
- Categories or useful links
- Copyright
- WordPress-generated footer content

## 5. Blog Archive

Create a professional archive layout.

Include:

- Page title
- Optional archive description
- Featured/latest article
- Responsive article grid
- Pagination

The archive must work correctly for:

- Blog
- Categories
- Tags
- Authors
- Dates
- Search results

Use proper WordPress pagination.

## 6. Single Blog Post

The single-post page is one of the most important parts of the theme.

Create an excellent reading experience.

Include:

- Category
- Post title
- Excerpt/subtitle if available
- Author information
- Published date
- Updated date where appropriate
- Reading time
- Featured image
- Article content
- Social sharing buttons
- Table of contents area
- Post tags
- Author box
- Previous/next post navigation
- Related posts
- Comments

### Article typography

Optimize long-form reading.

Use:

- Comfortable content width
- Appropriate line height
- Clear heading hierarchy
- Styled blockquotes
- Styled lists
- Code blocks
- Tables
- Images
- Captions
- Links
- Pull quotes where appropriate

Make WordPress Gutenberg content look polished without requiring a page builder.

## 7. Sidebar

Create a reusable sidebar component.

Possible widgets:

- Search
- About the website
- Recent posts
- Popular posts
- Categories
- Tags
- Newsletter
- Social links

The sidebar should be widget-ready using WordPress's widget system.

On mobile, move the sidebar below the main content.

## 8. WordPress Customization

Use the WordPress Customizer where appropriate.

Provide settings for things such as:

- Logo
- Site title
- Site tagline
- Accent color
- Header options
- Footer text
- Social media links
- Newsletter text
- Featured content settings

Avoid creating unnecessary custom settings.

Use WordPress-native functionality wherever possible.

## 9. Theme Architecture

Build the theme using a clean, maintainable structure.

A suggested structure:

```text
custom-blog-theme/
│
├── style.css
├── functions.php
├── index.php
├── front-page.php
├── home.php
├── single.php
├── page.php
├── archive.php
├── category.php
├── tag.php
├── author.php
├── search.php
├── 404.php
├── comments.php
├── header.php
├── footer.php
├── sidebar.php
│
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   ├── responsive.css
│   │   └── editor-style.css
│   │
│   ├── js/
│   │   └── main.js
│   │
│   └── images/
│
├── inc/
│   ├── customizer.php
│   ├── template-functions.php
│   ├── template-tags.php
│   ├── widgets.php
│   └── helpers.php
│
├── template-parts/
│   ├── content.php
│   ├── content-single.php
│   ├── content-card.php
│   ├── content-featured.php
│   ├── author-box.php
│   ├── related-posts.php
│   └── newsletter.php
│
└── screenshot.png
```

You may modify this structure if a better WordPress architecture is appropriate.

## 10. WordPress Coding Standards

Follow WordPress best practices.

Use:

- `wp_enqueue_style()`
- `wp_enqueue_script()`
- `wp_head()`
- `wp_footer()`
- `wp_body_open()`
- `wp_nav_menu()`
- `dynamic_sidebar()`
- `get_template_part()`
- `the_post()`
- `the_title()`
- `the_content()`
- `the_excerpt()`
- `the_post_thumbnail()`
- `get_the_author_meta()`
- `get_the_date()`
- `paginate_links()`

Use proper escaping and sanitization:

- `esc_html()`
- `esc_attr()`
- `esc_url()`
- `wp_kses_post()`
- `sanitize_text_field()`

Use WordPress nonces and capability checks wherever forms or admin functionality require them.

Do not use insecure raw database queries unless absolutely necessary.

## 11. Performance

The theme should be lightweight.

Requirements:

- Do not load unnecessary libraries
- Avoid jQuery unless actually needed
- Use vanilla JavaScript where practical
- Properly enqueue assets
- Use responsive images through WordPress functions
- Avoid huge JavaScript bundles
- Avoid unnecessary animations
- Minimize DOM complexity
- Use lazy loading where appropriate
- Avoid render-blocking resources where possible

## 12. SEO

Build the theme with SEO best practices.

Include:

- Semantic HTML5
- Correct heading hierarchy
- Proper `<article>`, `<main>`, `<header>`, `<nav>`, `<aside>`, and `<footer>`
- SEO-friendly post markup
- Proper image alt handling
- Canonical-friendly structure
- Breadcrumb-ready structure
- Open Graph-friendly markup where appropriate
- Schema-friendly HTML

Do not duplicate functionality that should normally be handled by an SEO plugin.

## 13. Accessibility

Follow WCAG-oriented accessibility practices.

Include:

- Keyboard navigation
- Visible focus states
- Proper labels
- ARIA only when necessary
- Sufficient color contrast
- Semantic HTML
- Accessible mobile navigation
- Skip-to-content link
- Proper button elements
- Alt text support
- Reduced-motion support

The mobile menu must be keyboard accessible and should properly manage its open/closed state.

## 14. Responsive Design

The theme must work well on:

- Large desktop
- Desktop
- Tablet
- Mobile

Do not simply shrink the desktop design.

Create intentional responsive layouts.

Pay particular attention to:

- Typography
- Navigation
- Article grids
- Featured image sizes
- Sidebar behavior
- Header
- Footer
- Tables
- Code blocks
- Images

## 15. Gutenberg Support

The theme must work properly with the WordPress Gutenberg editor.

Style common Gutenberg blocks, including:

- Paragraph
- Heading
- Image
- Gallery
- Quote
- Pullquote
- List
- Table
- Code
- Preformatted
- Buttons
- Separator
- Cover
- Columns
- Group

Make sure the editor experience is visually consistent with the frontend.

## 16. Admin / Content Requirements

The theme should work immediately after activation.

Do not require the user to manually edit PHP files to create basic content.

Use WordPress-native:

- Posts
- Pages
- Categories
- Tags
- Authors
- Menus
- Widgets
- Featured images
- Customizer

If custom post types are genuinely useful, explain why before adding them.

## 17. JavaScript Features

Keep JavaScript minimal but provide:

- Mobile navigation
- Search overlay/dropdown
- Optional sticky header behavior
- Smooth UI interactions
- Accessible menu state handling
- Optional back-to-top button

Do not add unnecessary sliders, carousels, or animation libraries.

## 18. CSS Requirements

Use modern CSS.

Prefer:

- CSS variables
- Flexbox
- CSS Grid
- `clamp()` for responsive typography
- Logical spacing
- Modern responsive units
- Reusable utility classes where appropriate

Do not rely on Bootstrap or Tailwind unless explicitly requested.

Create a coherent design system for:

- Typography
- Spacing
- Colors
- Buttons
- Cards
- Forms
- Containers
- Borders
- Shadows
- Responsive breakpoints

## 19. Demo Content

Create realistic placeholder/demo content so the theme looks complete during development.

Use fictional blog posts such as:

- Technology
- Web Development
- AI
- Design
- Business
- Productivity

Do not use copyrighted article content.

Use placeholder images or publicly usable image URLs only when necessary.

## 20. Theme Quality

The final result should feel like a premium production-ready WordPress theme.

Avoid:

- Generic Bootstrap-looking layouts
- Excessive rounded cards
- Excessive gradients
- Huge unnecessary animations
- Poor typography
- Cluttered interfaces
- Hard-coded content
- Inline CSS wherever avoidable
- Inline JavaScript wherever avoidable
- Plugin-dependent functionality for basic theme features

## 21. Development Approach

Build the theme incrementally.

### Phase 1
Set up:

- Theme files
- `style.css`
- `functions.php`
- Header
- Footer
- Basic templates
- Asset loading

### Phase 2
Build:

- Homepage
- Featured section
- Article cards
- Archive layouts
- Sidebar

### Phase 3
Build:

- Single article
- Author box
- Related posts
- Comments
- Social sharing
- Table of contents

### Phase 4
Build:

- Responsive design
- Mobile navigation
- Search
- Accessibility
- Gutenberg styling

### Phase 5
Optimize:

- Performance
- SEO
- Code quality
- Security
- WordPress standards

## 22. Important Rules

1. This must be a **real WordPress theme**, not a static HTML website.
2. Use WordPress's template hierarchy correctly.
3. Do not hard-code blog posts.
4. Do not require Elementor or another page builder.
5. Do not use a pre-built theme as the foundation.
6. Keep the theme modular and maintainable.
7. Keep frontend JavaScript minimal.
8. Make the design responsive from the beginning.
9. Make Gutenberg content look excellent.
10. Make the theme easy for another developer to understand.
11. Comment only where comments provide useful context.
12. Do not introduce unnecessary dependencies.
13. Follow WordPress security and escaping standards.
14. Make sure the theme can be installed as a normal `.zip` WordPress theme.
15. Before considering the project complete, check every template for PHP errors, missing functions, broken asset paths, accessibility issues, and responsive problems.

## 23. Final Deliverable

Provide the complete theme source code with all required files.

For every file:

1. Clearly identify the filename.
2. Provide the complete file contents.
3. Do not omit important sections with comments such as `// ...rest of code`.
4. Ensure all files work together.
5. Make sure paths and function names are consistent.
6. Explain how to install the theme in WordPress.
7. Explain how to configure the homepage, menu, logo, widgets, and Customizer settings.
8. Explain any assumptions or optional improvements separately.

The final theme should be functional immediately after installation and activation, while remaining easy to extend later.
