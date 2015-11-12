package main;

import Processors.ApplicationProcessor;
import Processors.IProcessor;
import Processors.JobProcessor;

public class main {

    public static void main(String[] args)
    {
        long startTime = System.currentTimeMillis();
        IProcessor processor = null;
        if(args.length > 0){
            System.out.println(args[0]);
            if(args.length == 4){
            	processor = new ApplicationProcessor(Integer.parseInt(args[1]), Integer.parseInt(args[2]),Integer.parseInt(args[3]), args[0]);
            }
           
            else if(args.length == 2){
                processor = new JobProcessor(Integer.parseInt(args[1]), args[0]);
            }
            else{
                System.out.println("invalid arguments");
                System.out.println("For applications, arguments should be: resume_path userId jobId applicationId");
                System.out.println("For job description, arguments should be: resume_path jobId ");

            }
            if(processor.process() == true){
                System.out.println("saving");
                processor.save();
            };
            
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
