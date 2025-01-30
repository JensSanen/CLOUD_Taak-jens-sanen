namespace werfplanning2.Models
{
    public class Project
    {
        public int projectId { get; set; }
        public string name { get; set; }
        public string? description { get; set; }
        public string location { get; set; }
        public string status { get; set; }
    }
}