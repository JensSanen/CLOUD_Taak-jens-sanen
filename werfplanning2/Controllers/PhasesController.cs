using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using werfplanning2.Data;
using werfplanning2.Models;

namespace werfplanning2.Controllers
{
    [Route("api/projects/{projectId}/phases")]
    [ApiController]
    public class PhasesController : ControllerBase
    {
        private readonly WerfplanningDbContext _context;
        private readonly ILogger<PhasesController> _logger;

        public PhasesController(WerfplanningDbContext context, ILogger<PhasesController> logger)
        {
            _context = context;
            _logger = logger;
        }

        // GET: api/projects/{projectId}/phases
        // Functie om alle fases van een project op te halen
        [HttpGet]
        public async Task<ActionResult<IEnumerable<Phase>>> GetProjectPhases(int projectId)
        {
            _logger.LogInformation("Fetching phases for projectId {ProjectId}", projectId);
            var phases = await _context.Phases
                .Where(p => p.projectId == projectId)
                .ToListAsync();

            if (phases == null || !phases.Any())
            {
                _logger.LogWarning("No phases found for projectId {ProjectId}", projectId);
                return NotFound();
            }

            _logger.LogInformation("Found {PhaseCount} phases for projectId {ProjectId}", phases.Count, projectId);
            return phases;
        }

        // GET: api/projects/{projectId}/phases/{phaseId}
        // Functie om een fase van een project op te halen
        [HttpGet("{phaseId}")]
        public async Task<ActionResult<Phase>> GetProjectPhase(int projectId, int phaseId)
        {
            _logger.LogInformation("Fetching phase {PhaseId} for project {ProjectId}", phaseId, projectId);
            var phase = await _context.Phases
                .FirstOrDefaultAsync(p => p.projectId == projectId && p.phaseId == phaseId);

            if (phase == null)
            {
                _logger.LogWarning("Phase {PhaseId} not found for project {ProjectId}", phaseId, projectId);
                return NotFound();
            }

            _logger.LogInformation("Successfully retrieved phase {PhaseId} for project {ProjectId}", phaseId, projectId);
            return phase;
        }

        // POST: api/projects/{projectId}/phases
        // Functie om een fase aan te maken voor een project
        // [FromBody] geeft aan dat de parameter uit de body van de request komt
        [HttpPost]
        public async Task<ActionResult<Phase>> CreateProjectPhase(int projectId, [FromBody] Phase phase)
        {
            _logger.LogInformation("Creating new phase for project {ProjectId}", projectId);

            phase.projectId = projectId;
            _context.Phases.Add(phase);
            await _context.SaveChangesAsync();

            _logger.LogInformation("Phase created with ID {PhaseId} for project {ProjectId}", phase.phaseId, projectId);
            return CreatedAtAction(nameof(GetProjectPhase), new { projectId = projectId, phaseId = phase.phaseId }, phase);
        }

        // PUT: api/projects/{projectId}/phases/{phaseId}
        // Functie om een fase van een project te updaten
        // [FromBody] geeft aan dat de parameter uit de body van de request komt
        [HttpPut("{phaseId}")]
        public async Task<IActionResult> UpdateProjectPhase(int projectId, int phaseId, [FromBody] Phase phase)
        {
            if (phaseId != phase.phaseId || projectId != phase.projectId)
            {
                _logger.LogWarning("Phase ID mismatch: URL ID {PhaseId} does not match body ID {BodyPhaseId} or Project ID mismatch", phaseId, phase.phaseId);
                return BadRequest();
            }

            _logger.LogInformation("Updating phase {PhaseId} for project {ProjectId}", phaseId, projectId);
            _context.Entry(phase).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
                _logger.LogInformation("Successfully updated phase {PhaseId} for project {ProjectId}", phaseId, projectId);
            }
            catch (DbUpdateConcurrencyException ex)
            {
                _logger.LogError(ex, "Concurrency error while updating phase {PhaseId} for project {ProjectId}", phaseId, projectId);
                return StatusCode(500, "An error occurred while updating the phase.");
            }

            return Ok(new { message = "Phase updated successfully" });
        }

        // DELETE: api/projects/{projectId}/phases/{phaseId}
        // Functie om een fase van een project te verwijderen
        [HttpDelete("{phaseId}")]
        public async Task<IActionResult> DeleteProjectPhase(int projectId, int phaseId)
        {
            _logger.LogInformation("Deleting phase {PhaseId} for project {ProjectId}", phaseId, projectId);
            var phase = await _context.Phases
                .FirstOrDefaultAsync(p => p.projectId == projectId && p.phaseId == phaseId);

            if (phase == null)
            {
                _logger.LogWarning("Phase {PhaseId} not found for project {ProjectId}", phaseId, projectId);
                return NotFound();
            }

            _context.Phases.Remove(phase);
            await _context.SaveChangesAsync();

            _logger.LogInformation("Successfully deleted phase {PhaseId} for project {ProjectId}", phaseId, projectId);
            return Ok(new { message = "Phase deleted successfully" });
        }
    }
}
