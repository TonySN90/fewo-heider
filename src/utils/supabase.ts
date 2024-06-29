import { createClient } from "@supabase/supabase-js";
const supabaseUrl = "https://mrbryvoeweebscyrxquv.supabase.co";
const supabaseKey =
  "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im1yYnJ5dm9ld2VlYnNjeXJ4cXV2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MTk2NzMwMDUsImV4cCI6MjAzNTI0OTAwNX0.a5UMZTnw92F-k4o1rQ07sHVEUg68ZnFwpVQoQHFTFLU";
const supabase = createClient(supabaseUrl, supabaseKey);

export default supabase;
