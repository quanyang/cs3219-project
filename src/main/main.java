package main;

import Processors.ApplicationProcessor;
import Processors.JobProcessor;

public class main {

    public static void main(String[] args)
    {
        long startTime = System.currentTimeMillis();
        if(args.length > 0){
            System.out.println(args[0]);
            if(args.length == 4){
               ApplicationProcessor applicationProcessor = new ApplicationProcessor(Integer.parseInt(args[1]), Integer.parseInt(args[2]),Integer.parseInt(args[3]), args[0]);
              if(applicationProcessor.run() == true){
                  System.out.println("saving");
                  applicationProcessor.save();
              };
            }
           
            else if(args.length == 2){
                JobProcessor jobProcessor = new JobProcessor(Integer.parseInt(args[1]), args[0]);
                jobProcessor.save();
            }
            else{
                System.out.println("invalid arguments");
                System.out.println("For applications, arguments should be: resume_path userId jobId applicationId");
                System.out.println("For job description, arguments should be: resume_path jobId ");

            }
            System.out.println("----The end of program----");
        }
        else {
            System.out.println("no arguments found");
        }
        
        long endTime   = System.currentTimeMillis();
        long totalTime = endTime - startTime;
        System.out.println(totalTime);

    }
}
