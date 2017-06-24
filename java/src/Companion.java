import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.nio.charset.StandardCharsets;
import java.util.Scanner;
import java.util.concurrent.TimeUnit;

/**
 * Created by Payton on 6/24/2017.
 */
public class Companion
{
    public static void main (String[] args) throws Exception
    {
        URL url = new URL("http://localhost/craigslist-companion/website/get_companions.php");
        Scanner scnr = new Scanner(url.openStream());
        String data = scnr.nextLine();
        data = data.replaceAll("\\+", "[+]");
        String[] companions = data.split("[+]");

        outerloop:
        while (true) {
            for (String companion : companions) {
                if (!companion.equals("[")) {
                    companion = companion.substring(1, companion.length());
                    String[] info = companion.split(":");
                    String city = info[0];
                    String item = info[1];
                    String email = info[2];
                    int total = Integer.parseInt(info[3]);
                    item = item.replace(" ", "%20");
                    String link = "https://" + city + ".craigslist.org/search/sss?sort=date&query=" + item;
                    System.out.println(link);

                    url = new URL(link);
                    Document doc = Jsoup.parse(url, 60000);
                    int count = 0;
                    for (Element element : doc.getElementsByClass("result-row")) {
                        count++;
                    }

                    int newItems = count - total;
                    total = count;

                    String parameters = "";
                    parameters += "email=" + URLEncoder.encode(email, "UTF-8") + "&";
                    parameters += "total=" + URLEncoder.encode(Integer.toString(total), "UTF-8") + "&";
                    parameters += "new=" + URLEncoder.encode(Integer.toString(newItems), "UTF-8");
                    byte[] postData = parameters.getBytes(StandardCharsets.UTF_8);
                    int postDataLength = postData.length;

                    HttpURLConnection conn = (HttpURLConnection) new URL("http://localhost/craigslist-companion/website/update_companion.php").openConnection();
                    conn.setDoOutput(true);
                    conn.setRequestMethod("POST");
                    conn.setUseCaches(false);

                    try (DataOutputStream wr = new DataOutputStream(conn.getOutputStream())) {
                        wr.write(postData);
                    }

                    BufferedReader br = new BufferedReader(new InputStreamReader(conn.getInputStream()));
                    String response = "";
                    while ((response = br.readLine()) != null) {
                        System.out.println(response);
                    }

                    br.close();
                    conn.disconnect();
                    if (newItems > 0) {
                        break outerloop;
                    }
                }
            }
            TimeUnit.SECONDS.sleep(30);
        }
    }
}
