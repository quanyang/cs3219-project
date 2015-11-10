package Parser;

import java.io.File;
import java.io.FileInputStream;

import org.apache.pdfbox.cos.COSDocument;
import org.apache.pdfbox.pdfparser.PDFParser;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.util.PDFTextStripper;

public class PdfConverter {
	public static String ToString(String filePath){
        String resumeLines;
        resumeLines = pdfToText(filePath);
//        if(resumeLines!= null)
//            System.out.println("pdf to text has just ended converting, the total characters are: "+resumeLines.length());
        return resumeLines;
	}
    private static String pdfToText(String fileName) {

        PDFParser parser = null;
        PDDocument pdDoc = null;
        COSDocument cosDoc = null;
        PDFTextStripper pdfStripper;
        String[] words = null;
        String parsedText = null;
        File file = new File(fileName);
        try {
            parser = new PDFParser(new FileInputStream(file));
            parser.parse();
            cosDoc = parser.getDocument();
            pdfStripper = new PDFTextStripper();
            pdDoc = new PDDocument(cosDoc);
            parsedText = pdfStripper.getText(pdDoc);
//            System.out.println(parsedText.replaceAll("[^A-Za-z0-9. ]+", ""));
           // words =  parsedText.split("\n");
        } catch (Exception e) {
            System.out.println("cannot open the file " + fileName);
        }
        try {
            if (cosDoc != null)
                cosDoc.close();
            if (pdDoc != null)
                pdDoc.close();
        } catch (Exception e1) {
            e1.printStackTrace();
        }
        
        return parsedText;
    }


}
