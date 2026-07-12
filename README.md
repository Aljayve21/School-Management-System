# School-Management-System

This project uses several layered design patterns:

**MVC (Model-View-Controller)**
- views/ — what the user sees
- controllers/ — handles requests, orchestrates logic
- models/ — represents database tables

**Repository Pattern**
- repositories/UserRepository.php, ScheduleRepository.php, etc.
- Each table gets its own class that handles all SQL/queries
- Controllers never write raw SQL — they call repo methods like findAll(), findByUserId()

**Service Layer Pattern**
- services/StudentService.php, GradeService.php, etc.
- Business logic lives here (validation rules, grade calculation, cross-table operations)
- Repositories only do CRUD; services do the "thinking"

**SOLID Principles (the big five):**
- S — Each class has one job (Repo = data, Service = logic, Controller = request handling)
- O — Interfaces (IRepository, IGradeCalculator) let you swap implementations without breaking other code
- L — Any class implementing an interface can be substituted (e.g., StudentRepository works anywhere IRepository is expected)
- I — Small focused interfaces instead of one giant IDatabaseHelper
- D — Controllers depend on service abstractions, not concrete DB calls

**Dependency Injection**
- Controllers create their own services/repos in constructors
- Services create their own repos in constructors
Request → Router → Controller → Service → Repository → Database
                                         → ActivityLogService (cross-cutting)
This is a common PHP architecture for real-world apps. Once you're comfortable here, next steps would be learning about Dependency Injection Containers (auto-wiring), Middleware chains (which you already have basic versions of), and PSR standards.
