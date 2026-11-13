<!--
Sync Impact Report
- Version change: 0.1.0 -> 1.0.0
- Modified principles: all template placeholders replaced with product governance.
- Added sections: Product Scope and Security Standards; Development Workflow.
- Removed sections: none.
- Templates requiring updates: none; existing templates remain compatible.
- Follow-up TODOs: none.
-->

# 3d Constitution

## Core Principles

### I. Public Discovery, Protected Digital Goods
The catalog MUST be publicly discoverable and useful to search engines, while commercial model files MUST remain inaccessible until an authorized purchase is confirmed. Preview assets and downloadable originals MUST be treated as separate artifacts. Every download authorization MUST be attributable to a customer and an order.

### II. Secure Commerce and Payment Truth
The system MUST treat the payment provider's confirmed notification as the source of truth for payment completion. It MUST support safe retries, prevent duplicate fulfillment, preserve an audit trail for order and payment state changes, and never expose a download solely because a client claims payment success.

### III. Customer Value Before Administrative Complexity
Each feature MUST support a clear customer or business outcome: discovering a model, understanding it, purchasing it, downloading it, or requesting a custom piece. Administrative capabilities MUST enable those outcomes, not become isolated internal complexity. The first release MUST prefer a small, complete purchasing journey over broad but unfinished functionality.

### IV. Delegable Administration with Least Privilege
The platform MUST support multiple administrator accounts with explicit roles and permissions. Administrative access MUST follow least privilege, record security-sensitive actions, and allow the primary owner to revoke delegated access without affecting customer purchases.

### V. Testable, Observable, Reversible Delivery
Business-critical flows MUST have automated tests and acceptance scenarios, especially authentication, payment transitions, download authorization, and administrative permissions. Failures MUST provide actionable logs without exposing secrets or model files. Changes to commerce or security behavior MUST be reversible or accompanied by a migration and rollback plan.

## Product Scope and Security Standards

The product is a marketplace for 3D models and custom-piece requests. The first release includes public catalog pages, web-based 3D previews, Google sign-in, Pix payment through Mercado Pago, protected downloads after confirmation, an administrative catalog, delegated administration, and manual custom-quote requests with WhatsApp support. Original files MUST NOT be stored in a public web-accessible location. Common source formats MAY be sold, while web previews MUST use a browser-suitable representation.

Secrets, payment credentials, OAuth credentials, and private file locations MUST remain outside version control. Customer and payment data MUST be handled with appropriate access controls, and production error responses MUST not reveal internal paths, credentials, or private assets.

## Development Workflow

Every feature MUST begin with a user-facing specification, prioritized scenarios, testable requirements, measurable success criteria, and explicit assumptions. Plans MUST identify security, payment, file-protection, and permission impacts. Implementation MUST include focused automated tests and a verification step for the affected user journey. A change is not ready when it merely works locally; it is ready when its acceptance scenarios, failure paths, and operational notes are covered.

## Governance

This constitution governs product specifications, plans, implementation, and reviews. Amendments require a documented rationale, a semantic version update, and review of dependent specifications and templates. Major versions indicate backward-incompatible governance changes; minor versions add or materially expand principles; patch versions clarify wording without changing intent.

All feature reviews MUST check compliance with these principles. Any exception MUST be explicit in the relevant specification or plan, include its rationale, and define a follow-up correction or expiry. The constitution is reviewed when payment, authentication, file delivery, administrator permissions, or product scope changes.

**Version**: 1.0.0 | **Ratified**: 2026-11-13 | **Last Amended**: 2026-11-13
