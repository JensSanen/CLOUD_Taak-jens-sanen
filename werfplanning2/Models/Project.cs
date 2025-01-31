namespace werfplanning2.Models
{
    // Model klasse die een project representeert
    public class Project
    {
        public int projectId { get; set; }
        public string name { get; set; }
        public string? description { get; set; }
        public string location { get; set; }
        public string status { get; set; }
    }
}