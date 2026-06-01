<p align="center">
  <img src="public/static/img/logo/logo.png" width="150" alt="OSEM Logo">
</p>

<h1 align="center">OSEM — Online Summon and Enforcement Management</h1>
<p align="center">A centralized web-based enforcement and vehicle management system for IIUM's Office of Security Management.</p>

---

## Table of Contents
- [About](#about)
- [Problem Statement](#problem-statement)
- [Objectives](#objectives)
- [Features](#features)
- [User Roles](#user-roles)

---

## About

The **Office of Security Management (OSeM)** at the International Islamic University Malaysia (IIUM) is responsible for maintaining safety and order across campus. Two of its primary duties include managing campus vehicle access through a sticker registration process and enforcing campus regulations by issuing disciplinary compounds.

Currently, the digital infrastructure supporting these operations is highly fragmented. Students and staff must navigate a disjointed web of legacy systems — viewing basic records on the general iMaalum portal, applying for vehicle stickers through an isolated Auxiliary Police System (APS), and managing or appealing campus violations via a separate Compoundable Offence System (ICOS) platform. Furthermore, the enforcement process itself remains fundamentally manual; security officers issue physical compound slips on vehicle windshields during patrols, which must be manually re-typed into the office system hours later.

**OSEM** is proposed to resolve these administrative bottlenecks. Deliberately designed for OSeM, this dedicated web-based application bridges the gap between vehicle registration and disciplinary enforcement — providing a centralized ecosystem where users can seamlessly register vehicles and track compound statuses, while empowering security officers and administrators to manage applications, record violations digitally, and oversee campus enforcement through a single, unified dashboard.

---

## Problem Statement

| # | Issue | Description |
|---|-------|-------------|
| 1 | **Fragmented User Experience** | Students and staff lack a single point of truth. To manage vehicle compliance, users are forced to cross-reference the iMaalum portal, APS for stickers, and ICOS for compounds — causing confusion and delays. |
| 2 | **Inefficient Double-Entry Enforcement** | Officers write physical compound slips during patrols and manually re-enter them into the system later, creating double work, delaying real-time visibility, and increasing the risk of human error. |
| 3 | **Fragmented Enforcement Visibility** | No centralized interface links a vehicle's registration status to its disciplinary history. Administrators and officers cannot instantly verify if a vehicle receiving a compound holds a valid campus sticker. |
| 4 | **Cumbersome Resolution Tracking** | Users have no dedicated, real-time dashboard to monitor the status of their vehicle applications, outstanding compound payments, or appeal results. |

---

## Objectives

### Current Development Objectives

- Develop a centralized web-based application that unifies vehicle sticker registration and campus compound management into a single, cohesive platform.
- Streamline the enforcement process by providing officers with a structured digital interface to record and issue compounds, eliminating delayed physical-to-digital data entry.
- Provide a role-based, self-service web app where students and staff can apply for vehicle stickers, view compound history, and submit formal appeals online.
- Equip OSeM administrators with comprehensive oversight tools that link vehicle application data directly with enforcement history.

### Long-Term Objectives

| Goal | Description |
|------|-------------|
| System Integration | Full API integration with iMaalum and IIUM's CAS portal for seamless login and unified campus data sharing. |
| Mobile Enforcement | Mobile-responsive interfaces allowing officers to issue compounds in real-time via smartphones during patrols. |
| AI Automation | AI-driven license plate recognition via patrol cameras to automatically detect and log parking violations. |
| Office Management | Expand the platform to track staff schedules, manage guest vehicle entries, and generate data-driven income insights. |

---

## Features

### Authentication & Role-Based Access
All users sign in using their matric or staff number and password. Access to pages and actions is controlled by role, ensuring each party only sees what is relevant to them. To simulate IIUM's closed Central Authentication Service (CAS), public registration is disabled — only administrators can manage user accounts.

### Vehicle Registration & Sticker Management
Users register their vehicle by submitting a plate number, vehicle type, and reason for campus access. This application also serves as the vehicle sticker request. Officers review and approve or reject submissions. Administrators can manage all records.

### Compound Issuance
Officers issue compounds by looking up a plate number, viewing the vehicle's registration status, and selecting the relevant offence. Each compound covers one offence. Officers can process multiple vehicles in a single session.

### Compound Appeals
Users may appeal a compound by submitting a written reason. One appeal is allowed per compound. If approved, the compound amount is halved. If rejected, the full amount remains payable.

### Payment (Simulated)
For this prototype, IIUM EzPay gateway integration is simulated. Users confirm payment directly within the platform, which automatically updates the compound's status to **Paid**.

### Admin Dashboard
Displays the number of compounds issued, count of unresolved applications and appeals, total payment amount received, and officer activity.

### Offence Type Management
Administrators configure the list of offence types and their preset compound amounts, which officers refer to when issuing compounds.

---

## User Roles

| Role | Access |
|------|--------|
| **Admin** | Full system access — user management, offence configuration, vehicle records, and dashboard. |
| **Officer** | Operational access — reviewing vehicle applications, issuing compounds, and resolving appeals. |
| **User** | Personal access only — registering vehicles, viewing own compounds, and submitting appeals. |

---

## Tech Stack

- **Framework:** Laravel 11
- **Frontend:** AdminKit (Bootstrap 5) + Blade Templates
- **Auth:** Laravel Jetstream + Fortify
- **Database:** MySQL

---

## Contributors

| Name | Role |
|------|------|
| **Anwar Syafiq** | Project Lead |
| **Danish Rahmat** | Developer |
| **Danish Aiman** | Developer |
| **Aizat Adnan** | Developer |
| **Fadli Baharuddin** | Developer |

---

<p align="center">
  Developed for BIIT 2305 — Web Application Development<br>
  International Islamic University Malaysia (IIUM)
</p>
