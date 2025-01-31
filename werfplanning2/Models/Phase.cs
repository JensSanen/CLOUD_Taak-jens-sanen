namespace werfplanning2.Models
{
    // Model klasse die een fase representeert
    public class Phase
    {
        public int phaseId { get; set; }
        public string name { get; set; }
        public string? description { get; set; }
        public string startDate { get; set; }
        public string endDate { get; set; }
        public int projectId { get; set; }
    }
}