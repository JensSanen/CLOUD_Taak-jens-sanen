using Microsoft.EntityFrameworkCore;
using werfplanning2.Models;

namespace werfplanning2.Data
{
    // DbContext klasse die de database representeert
    public class WerfplanningDbContext : DbContext
    {
        public DbSet<Project> Projects { get; set; }
        public DbSet<Phase> Phases { get; set; }

        public WerfplanningDbContext(DbContextOptions<WerfplanningDbContext> options) : base(options) { }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);
        }
    }
}