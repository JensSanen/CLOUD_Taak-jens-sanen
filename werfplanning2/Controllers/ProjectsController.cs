using Microsoft.AspNetCore.Mvc;
using werfplanning2.Data;
using werfplanning2.Models;
using Microsoft.EntityFrameworkCore;

namespace werfplanning2.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class ProjectsController : ControllerBase
    {
        private readonly WerfplanningDbContext _context;
        private readonly ILogger<ProjectsController> _logger;

        public ProjectsController(WerfplanningDbContext context, ILogger<ProjectsController> logger)
        {
            _context = context;
            _logger = logger;
        }

        [HttpGet]
        public async Task<ActionResult<IEnumerable<Project>>> GetProjects()
        {
            _logger.LogInformation("Getting all projects");
            var projects = await _context.Projects.ToListAsync();
            _logger.LogInformation($"Found {projects.Count} projects");
            return projects;
        }

        [HttpGet("{projectId}")]
        public async Task<ActionResult<Project>> GetProject(int projectId)
        {
            _logger.LogInformation($"Getting project with id {projectId}");
            var project = await _context.Projects.FindAsync(projectId);
            if (project == null)
            {
                _logger.LogWarning($"Project with id {projectId} not found");
                return NotFound();
            }
            return project;
        }

        [HttpPost]
        public async Task<ActionResult<Project>> CreateProject([FromBody] Project project)
        {
            if (!ModelState.IsValid)
            {
                _logger.LogWarning("Invalid model state: {modelState}", ModelState);
                return BadRequest(ModelState);
            }
            
            _logger.LogInformation("Creating new project: {projectName}", project.name);
            _context.Projects.Add(project);
            await _context.SaveChangesAsync();
            _logger.LogInformation("Project created with id {projectId}", project.projectId);
            return CreatedAtAction(nameof(GetProject), new { projectId = project.projectId }, project);
        }

        [HttpPut("{projectId}")]
        public async Task<IActionResult> UpdateProject(int projectId, [FromBody] Project project)
        {
            if (!ModelState.IsValid)
            {
                _logger.LogWarning("Invalid model state: {modelState}", ModelState);
                return BadRequest(ModelState);
            }
            if (projectId != project.projectId)
            {
                _logger.LogWarning("Project id {projectId} does not match project id {project.projectId}", projectId, project.projectId);
                return BadRequest();
            }
            _logger.LogInformation("Updating project with id {projectId}", projectId);
            _context.Entry(project).State = EntityState.Modified;
            await _context.SaveChangesAsync();
            _logger.LogInformation("Project updated with id {projectId}", projectId);
            return Ok(new { message = "Project updated successfully" });
        }

        [HttpDelete("{projectId}")]
        public async Task<IActionResult> DeleteProject(int projectId)
        {
            _logger.LogInformation("Deleting project with id {projectId}", projectId);
            var project = await _context.Projects.FindAsync(projectId);
            if (project == null)
            {
            _logger.LogWarning("Project with id {projectId} not found", projectId);
            return NotFound();
            }

            _context.Projects.Remove(project);
            await _context.SaveChangesAsync();
            _logger.LogInformation("Project deleted with id {projectId}", projectId);
            return Ok(new { message = "Project deleted successfully" });
        }
    }
}