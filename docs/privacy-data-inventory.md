# ECRC — what personal data the site holds, and why

Prepared 28 September 2026 from the application code, as the factual basis
for the Privacy & Data Protection Notice. It records **what is collected,
why, who sees it and how long it is kept**. It deliberately does **not**
choose a GDPR lawful basis: that is for ECRC to decide per purpose (see
"Decisions needed" at the end) before the notice is published.

## 1. Parents (registered accounts)

| Data | Why | Who can see it |
|---|---|---|
| First and last name | Identify the account; shown on groups only if the parent chooses real name | Admin; other visitors only where the parent chose "real name" (otherwise an anonymous code such as AD00008) |
| Email address | Log in; account and group emails | Admin only |
| Password | Log in | Nobody — stored only as a one-way hash |
| Phone number (optional; required if they choose text/WhatsApp contact) | Contact | Admin; convenors do not see it |
| Preferred contact method (email / text / WhatsApp) | How to contact them | Admin |
| Public name choice (real name / anonymous code) | Controls what others see | — |
| Supporter categories ticked (e.g. willing to volunteer, teacher) | Know who can help with what | Admin |
| Account dates (invited, registered, onboarded, reminders sent) | Run the sign-up process | Admin |

## 2. Children (registered by a parent)

| Data | Why | Who can see it |
|---|---|---|
| First name (required) | Genuine registration; shown to other logged-in parents **only if** the parent chooses "code name + first name" | Admin; logged-in parents only if the parent opted in |
| Last name (optional) | Identify the child, avoid duplicates | Admin only — never shown to other parents or the public |
| Rebel code name (chosen by the family) | Show participation without real names | Logged-in parents |
| School and class | Show support class by class; form groups | Public sees **numbers only**; logged-in parents see code names by class ("general grade" or "specific class", as the parent chooses) |
| Intended secondary school (6th class: a school, "Undecided", or a typed unlisted school) | Transition groups for Rebels going to the same school | Admin |
| Co-parents linked to the child | Both parents can manage the child | The two parents; admin |
| Admin review status of the registration | Check registrations are genuine | Admin |
| Activities the child is signed up for | Headcount and safety for the activity | Admin (People page: child's name, school, class, parent's contact details). Public sees a count only |

## 3. Supporters (no account)

Name, email, optional phone, preferred contact method, optional message,
categories ticked — to count support and contact people who offered help.
Admin only. Can be deactivated by admin.

## 4. Activities

- **Volunteers:** name, email, phone, optional note (parents or supporters) —
  so the convenor can organise helpers. Admin only.
- **Suggestions** (party page): the suggestion text and which parent sent it.
  Shown publicly **without** a name, only after admin approval.
- **WhatsApp group links:** shown only to parents who signed up or
  volunteered. Joining a WhatsApp group is the parent's own action, and
  everyone in a group can see members' phone numbers (a WhatsApp matter,
  not ECRC data).

## 5. Action groups

Membership and role (convenor/member). Group pages list members **by name
only if they consented** ("show my name"), otherwise by anonymous code.

## 6. Workshop RSVPs

Name, email, optional phone, number attending, optional note — numbers for
the October meetings. Admin only.

## 7. Schools and school requests

- School contact names and emails (principal, vice-principal, secretary) —
  to send the school invitation. Admin only.
- "Add my school" / "request a secondary school": the requester's name,
  email, optional phone. Admin only.

## 8. Technical

- **Sessions** (database): IP address and browser type while logged in, for
  security; expire after 120 minutes of inactivity.
- **Analytics:** Plausible — no cookies, no personal data, not on admin
  pages.
- **Browser storage:** remembers that the site notice / "Early days" popup
  was seen. Not personal data; never sent to the server.
- **Admin audit log:** records admin actions (e.g. deletions, convenor
  changes), which can include the email of the person affected.

## 9. Emails the site sends

Account and password emails; co-parent invitations; reminders for
unfinished registrations; group emails (added to a group, made convenor);
school invitations; workshop RSVP confirmations. A copy of several
notification emails goes to info@eastcorkreclaimchildhood.ie for oversight.

## 10. Retention — what the code actually does today

- **Unfinished parent registrations:** reminders at 3 and 6 days, account
  **deleted at 9 days** (never if it already has children).
- **Everything else is kept until deleted** by the parent (a parent can
  remove their own child) or by admin. There is no automatic deletion of
  children, parents, supporters, volunteers, sign-ups or RSVPs.
- Deleting a parent's child deletes its school link and activity sign-ups.

## 11. Where the data is

| Service | Role | Where | Data |
|---|---|---|---|
| Vultr (The Constant Company, LLC) | Server hosting | Piscataway, New Jersey, **USA** (from the server's IP, 28 Sep 2026) | Everything in the database |
| RunCloud | Server management panel | — | Access to the server |
| Resend, Inc. | Sends the site's emails (`smtp.resend.com`) | Sending domain set up in Resend's **EU (Ireland)** region, but Resend states it stores message content and logs in the **USA** | Recipients' names and addresses, email content |
| Zoho | Hosts the info@ inbox | — | Oversight copies of site emails; mail sent to info@ |
| Plausible | Analytics | — | No personal data |

Code is on GitHub (no personal data).

Transfer safeguards found (28 Sep 2026): Resend has a pre-signed GDPR
Article 28 DPA with Standard Contractual Clauses and is certified under the
EU-US Data Privacy Framework (resend.com/security/gdpr). Vultr offers a
GDPR DPA (docs.vultr.com); no Data Privacy Framework certification found.

---

## Decisions made

- **Controller (28 Sep 2026):** Peter O'Sullivan, Interim Convenor, East
  Cork Reclaim Childhood. Contact for data requests:
  info@eastcorkreclaimchildhood.ie.
- **Sharing (28 Sep 2026):** ECRC never shares personal data with anyone —
  not schools, SFCI or any other organisation — other than the services in
  section 11 that run the site.
- **Hosting (28 Sep 2026):** keep the server in the USA (Vultr, New
  Jersey), relying on Vultr's GDPR DPA; Resend covered by its DPA and the
  EU-US Data Privacy Framework. The site will move to an EU data centre if
  ECRC becomes established and registers as a not-for-profit. **Action for
  Peter:** request Vultr's DPA via a support ticket in the Vultr Console,
  and download Resend's signed DPA from the Resend account.
- **Retention (28 Sep 2026)** — to be enforced by automatic deletion
  (not yet built):

  | Data | Kept |
  |---|---|
  | Child's registration | Until the parent deletes it, or the end of the child's first year in secondary school |
  | Parent account | While they have a registered child or until they close it; deleted 12 months after their last child's record ends |
  | Activity sign-ups and volunteers | Deleted 3 months after the activity (weekly activities: when the parent withdraws) |
  | Workshop RSVPs | Deleted 3 months after the meetings |
  | Supporters | Until they ask to be removed, or 2 years with no contact |
  | Unfinished registrations | 9 days (already automatic) |
  | Admin audit log | 2 years |

## Decisions needed before the notice is published

1. **Lawful basis for each purpose** (e.g. consent, legitimate interests) —
   parent accounts; children's registrations; showing code names to other
   parents; activity sign-ups; volunteers; supporters; RSVPs; school
   contacts; security logs.
2. **Children's data:** parents register children aged roughly 9–12. How
   parental authority/consent is recorded, and how a child's own rights are
   handled.
3. **Retention periods** — e.g. what happens to a child's record after
   secondary transition, to past activity sign-ups, volunteers, RSVPs after
   the event, supporters who go quiet.
4. **Controller identity and contact:** who is the data controller (Peter as
   convenor? ECRC as an unincorporated group?) and the contact address for
   data requests.
5. **Processors / transfers:** confirm the Vultr server region (EU or not)
   and name the email provider; whether contracts (DPAs) are in place.
6. **Sharing:** confirm data is never shared with schools, SFCI or anyone
   else unless stated.
