# Feature Specification: 3D Model Marketplace

**Feature Branch**: \`001-3d-marketplace\`

**Created**: 2026-11-13

**Status**: Draft

**Input**: User description: "Create a marketplace for 3D models with public Google-indexable pages, interactive previews, protected downloads after Mercado Pago Pix payment, Google login, manual custom-piece requests via WhatsApp, and delegated administrator accounts."

## User Scenarios & Testing

### User Story 1 - Discover and evaluate a model (Priority: P1)

As a visitor, I want to find a model through search or the catalog and inspect its preview and information before deciding whether it is useful.

**Why this priority**: Public discovery is the foundation for attracting customers.

**Independent Test**: Publish one model, open its page anonymously, and verify that the visitor can inspect the preview and commercial information without receiving the original file.

**Acceptance Scenarios**:

1. **Given** a published model, **When** a visitor opens its page, **Then** the page shows title, description, preview, supported formats, price, and purchase action.
2. **Given** a visitor searches the catalog, **When** they submit a relevant term, **Then** matching published models are returned.
3. **Given** an anonymous visitor views a model page, **When** they inspect page resources, **Then** the protected original file is not exposed.

### User Story 2 - Purchase and download a model (Priority: P1)

As a customer, I want to sign in with Google, pay using Pix, and download the purchased files after payment confirmation.

**Why this priority**: Paid delivery is the core business transaction.

**Independent Test**: Complete a test Pix payment, confirm the provider notification, and verify that only the paid customer receives an authorized download.

**Acceptance Scenarios**:

1. **Given** an unsigned visitor, **When** they begin checkout, **Then** the system requests Google authentication.
2. **Given** a signed-in customer, **When** they start checkout, **Then** the system creates a pending order and presents Pix instructions.
3. **Given** a pending order, **When** Mercado Pago confirms payment, **Then** the order becomes paid and the account shows the download.
4. **Given** an unpaid or failed order, **When** the customer requests the file, **Then** access is denied.
5. **Given** a duplicate payment notification, **When** it is processed, **Then** fulfillment happens only once.

### User Story 3 - Manage catalog and delegated administration (Priority: P1)

As the owner or an authorized administrator, I want to manage models, files, definitions, prices, publication, and delegated permissions.

**Why this priority**: The marketplace requires an operable catalog and safe delegation.

**Independent Test**: Create a catalog administrator, publish a complete model with that account, and verify that unrelated financial functions remain unavailable.

**Acceptance Scenarios**:

1. **Given** a catalog administrator, **When** they create a model, **Then** they can provide descriptions, technical definitions, supported formats, price, preview, original files, and status.
2. **Given** incomplete commercial information, **When** publication is attempted, **Then** the system identifies missing information and blocks publication.
3. **Given** the owner, **When** they create or update an administrator, **Then** they can assign and later revoke permissions.
4. **Given** a permission or catalog change, **When** it succeeds, **Then** the responsible administrator and change are recorded.

### User Story 4 - Request a custom piece (Priority: P2)

As a visitor or customer, I want to submit requirements and attachments for a custom piece and continue through WhatsApp.

**Why this priority**: Custom work creates a second revenue path.

**Independent Test**: Submit a request with details and an attachment, verify it is recorded, and verify the WhatsApp follow-up action.

**Acceptance Scenarios**:

1. **Given** a visitor or customer, **When** they submit a request, **Then** the system records contact details, requirements, and allowed attachments.
2. **Given** a submitted request, **When** it is accepted, **Then** the user receives a clear WhatsApp contact action.
3. **Given** a service administrator, **When** they review a request, **Then** they can update status and internal notes without changing the original submission.

### Edge Cases

- A model may contain multiple source files and a separate browser preview.
- Payment may be confirmed after the customer closes the browser.
- Payment may be rejected, canceled, refunded, or disputed.
- A model may be unpublished after purchase; prior purchases remain visible unless access is explicitly revoked.
- Checkout may be opened in multiple tabs; duplicate orders and fulfillment must be prevented or reconciled.
- Google, Mercado Pago, upload, or download services may be temporarily unavailable.
- Unsupported or malicious model files and request attachments must be rejected or quarantined.

## Requirements

### Functional Requirements

- **FR-001**: The system MUST provide public pages for published models with title, description, preview, specifications, formats, price, and purchase action.
- **FR-002**: The system MUST make public catalog pages indexable while excluding administration, customer, payment, and download pages from public discovery.
- **FR-003**: The system MUST provide an interactive browser-compatible preview without exposing the commercial original.
- **FR-004**: Authorized administrators MUST be able to create, edit, publish, unpublish, archive, and version models.
- **FR-005**: The system MUST support common 3D formats and multiple files per model.
- **FR-006**: Model definitions MUST support dimensions, units, intended use, compatibility notes, tags, categories, and licensing terms.
- **FR-007**: Administrators MUST be able to set prices and publication status, with an audit history of material changes.
- **FR-008**: Customers MUST authenticate through Google before a purchase or download is associated with an account.
- **FR-009**: The system MUST create a pending order before showing Pix payment instructions.
- **FR-010**: Pix payment instructions MUST be specific to the order and amount.
- **FR-011**: Payment and order status MUST change only from validated provider confirmation or an authorized, audited administrative action.
- **FR-012**: A model MUST be downloadable only when its order is paid and not revoked.
- **FR-013**: Original files MUST be inaccessible directly and delivered through an authorized, expiring or revocable download.
- **FR-014**: Duplicate notifications or repeated customer actions MUST NOT cause duplicate fulfillment.
- **FR-015**: Customers MUST be able to see orders, payment status, purchases, and available downloads.
- **FR-016**: The owner MUST be able to create, edit, deactivate, and assign permissions to administrators.
- **FR-017**: The system MUST enforce least-privilege permissions for catalog, service, orders, payments, and platform administration.
- **FR-018**: Security-sensitive actions, permission changes, payment overrides, publication changes, and download authorizations MUST be auditable.
- **FR-019**: Visitors or customers MUST be able to submit custom-piece requests with contact details, requirements, and permitted attachments.
- **FR-020**: Service administrators MUST be able to review requests, update status, and add internal notes without changing the original submission.
- **FR-021**: The system MUST provide a WhatsApp contact action for personalized service.
- **FR-022**: Model files and attachments MUST be validated for type, size, integrity, and publication eligibility.
- **FR-023**: Failure messages MUST be actionable and MUST NOT reveal secrets, private paths, or private assets.
- **FR-024**: Order, payment, access, and audit information MUST be retained for support and dispute investigation.

### Key Entities

- **Model**: Catalog product with description, preview, definitions, formats, price, status, license, and versions.
- **Model File**: Original, preview, or supplementary asset with visibility and permitted-use classification.
- **Customer**: Google-authenticated person who can purchase, download, and request custom work.
- **Administrator**: Staff account with delegated permissions and auditable identity.
- **Order**: Purchase intent containing model items, amount, currency, and lifecycle status.
- **Payment**: Mercado Pago transaction associated with an order and its Pix confirmation data.
- **Download Authorization**: Permission connecting a customer, paid order, model file, validity, and revocation state.
- **Custom Request**: Manual service request with requirements, attachments, status, notes, and WhatsApp context.
- **Audit Event**: Record of security, commercial, permission, publication, or fulfillment activity.

## Success Criteria

### Measurable Outcomes

- **SC-001**: At least 95% of first-time visitors can find the purchase action and specifications on a public model page without assistance.
- **SC-002**: A signed-in customer can create a Pix order in under 3 minutes when the provider is available.
- **SC-003**: At least 98% of confirmed paid orders expose the authorized download within 2 minutes of validated confirmation.
- **SC-004**: Zero unpaid or unauthenticated test cases can retrieve an original model file.
- **SC-005**: At least 95% of representative customers can complete Google sign-in, payment initiation, and download without support.
- **SC-006**: A catalog administrator can publish a complete model in under 10 minutes.
- **SC-007**: 100% of permission, payment override, publication, and download authorization changes have an attributable audit record.
- **SC-008**: At least 90% of valid custom requests are recorded successfully and provide usable WhatsApp follow-up.

## Assumptions

- The first release sells digital model files; physical manufacturing and shipping are out of scope.
- Mercado Pago Pix is the initial payment method; other payment methods may be added later.
- Google sign-in is the primary customer authentication method; password login is out of scope for the first release.
- Browser previews use a converted or separately uploaded web-compatible representation; original formats remain protected downloads.
- Mercado Pago, Google OAuth, and WhatsApp credentials are supplied through secure environment configuration.
- Custom-piece pricing is manual and is not calculated automatically in the first release.
- WhatsApp is the primary personalized service channel; in-platform real-time chat is out of scope.
- The first release targets responsive web browsers; a native mobile application is out of scope.
- Prices use a configured business currency and tax/legal requirements are confirmed before launch.
