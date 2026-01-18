import java.awt.*;
import java.awt.event.*;
import java.io.File;
import java.io.IOException;
import javax.imageio.ImageIO;
import javax.sound.sampled.*;
import javax.swing.*;
import java.io.FileWriter;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter; 



class JPanelWithBackground extends JPanel {
    private Image backgroundImage;

    public JPanelWithBackground(String fileName) throws IOException {
        backgroundImage = ImageIO.read(new File(fileName));
    }

    @Override
    protected void paintComponent(Graphics g) {
        super.paintComponent(g);

        // Draw the background image.
        if (backgroundImage != null) {
            g.drawImage(backgroundImage, 0, 0, getWidth(), getHeight(), this);
        }
    }
}

class Write {
    Write(String text) {
        try {
            String message = LocalDateTime.now().format(DateTimeFormatter.ofPattern("[dd/MM/yyyy] [HH:mm:ss] ")) + text;
            FileWriter writer = new FileWriter("log.txt", true);
            writer.write(message + "\n");
            writer.close();
        } catch (IOException ex) {
            JOptionPane.showMessageDialog(null, "Error: " + ex.getMessage());
        }
    }
}


public class Jon {
    private Clip clip; // Add a field to store the Clip object

    private void playMusic(String musicFile) {
        try {
            AudioInputStream audioInputStream = AudioSystem.getAudioInputStream(new File(musicFile).getAbsoluteFile());
            clip = AudioSystem.getClip(); // Store the Clip object in the field
            clip.open(audioInputStream);
            clip.start();
            clip.loop(Clip.LOOP_CONTINUOUSLY);
        } catch (UnsupportedAudioFileException | IOException ex) {
            ex.printStackTrace();
        } catch (LineUnavailableException ex) {
            ex.printStackTrace();
        }
    }

    private void stopMusic() {
        if (clip != null && clip.isRunning()) {
            clip.stop();
        }
    }

    Jon() {
        JFrame frame = new JFrame();
        JPanelWithBackground panel = null;

        try {
            panel = new JPanelWithBackground("BG1.png");
        } catch (IOException e) {
            e.printStackTrace();
            return;
        }

        frame.setContentPane(panel);
        frame.setTitle("Mirangelica");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setSize(720, 420);
        frame.setLocationRelativeTo(null);
        frame.setLayout(null); // Set layout to null
        frame.setResizable(false);

        new Write("Program Started");
    



        

        TrayIcon trayIcon = new TrayIcon(Toolkit.getDefaultToolkit().getImage("miratray.png"));
        trayIcon.setToolTip("Running");

        Runtime.getRuntime().addShutdownHook(new Thread() {
            public void run() {
                try {
                    SystemTray.getSystemTray().remove(trayIcon);
                } catch (Exception e) {
                    System.out.println(e);
                }
            }
        });
        
        trayIcon.addMouseListener(new MouseAdapter() {
            @Override
            public void mouseClicked(MouseEvent e) {
                super.mouseClicked(e);
                if (frame.isShowing()) {
                    frame.setAlwaysOnTop(true); // Bring the frame to the front
                } else {
                    new Jon();
                }
            }
        });



        //Icons

        try{
            SystemTray.getSystemTray().add(trayIcon);
        }catch(Exception e){
            System.out.println(e);
        }
    



        ImageIcon image = new ImageIcon("miralogo.png");             // Logo
        frame.setIconImage(image.getImage());


        JLabel icon1 = new JLabel(); 
        icon1.setIcon(new ImageIcon("ange1.png"));                // Angelo
        Dimension size1 = icon1.getPreferredSize(); 
        icon1.setBounds(480, 100, size1.width, size1.height);
        icon1.setOpaque(false);


        JLabel icon2 = new JLabel(); 
        icon2.setIcon(new ImageIcon("mira2.png"));                // Mira
        Dimension size2 = icon2.getPreferredSize(); 
        icon2.setBounds(5, 70, size2.width, size2.height);
        icon2.setOpaque(false);

        JLabel icon3 = new JLabel(); 
        icon3.setIcon(new ImageIcon("mov3.gif"));                // Title Screen
        Dimension size3 = icon3.getPreferredSize(); 
        icon3.setBounds(235, 20, size3.width, size3.height);
        icon3.setOpaque(false);

     

        // Buttons


        Icon B_icon1 = new ImageIcon("start.PNG");
        JButton button1 = new JButton(B_icon1);
        int button1Width = 140;
        int button1Height = 40;
        int button1X = 290;
        int button1Y = 200;

        
        button1.setBounds(button1X, button1Y, button1Width, button1Height);
        button1.addActionListener((ActionEvent ae) -> {
            stopMusic();
            SystemTray.getSystemTray().remove(trayIcon);
            frame.setVisible(false);
            new Write("Started the game");
            new Jon1();
        });

        Icon B_icon2 = new ImageIcon("exit1.PNG");
        JButton button2 = new JButton(B_icon2);
        int button2Width = 140;
        int button2Height = 40;
        int button2X = 290;
        int button2Y = 260;


        
        button2.setBounds(button2X, button2Y, button2Width, button2Height);     
        button2.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent ae) {
                stopMusic();
                frame.dispose();
                new Write("Exited the program");
    
                SystemTray.getSystemTray().remove(trayIcon);
            }
        });

        Icon B_icon3 = new ImageIcon("unmute.PNG");
        JButton button3 = new JButton(B_icon3);
        int button3Width = 40;
        int button3Height = 34;
        int button3X = 650;
        int button3Y = 10;

        Icon B_icon4 = new ImageIcon("mute.PNG");
        JButton button4 = new JButton(B_icon4);
        int button4Width = 40;
        int button4Height = 34;
        int button4X = 650;
        int button4Y = 10;

        


        button3.setBounds(button3X, button3Y, button3Width, button3Height);
        button3.addActionListener(new ActionListener() {
        public void actionPerformed(ActionEvent ae) {
            stopMusic();
            new Write("Program has been muted");
            button3.setVisible(false); // Hide mute button
            button4.setVisible(true);  // Show unmute button
        }
    });

        button4.setBounds(button4X, button4Y, button4Width, button4Height);
        button4.addActionListener(new ActionListener() {
        public void actionPerformed(ActionEvent ae) {
            playMusic("bgsong1.wav");
            new Write("Program has been unmuted");
            button4.setVisible(false); // Hide unmute button
            button3.setVisible(true);  // Show mute button
        }
    });




      
        // Close music when program exit

        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent ae) {
                new Write("Exited the program\n");
                stopMusic();
                System.exit(0);
            }
        });
       



        // Add components to the frame
        frame.add(icon1);
        frame.add(icon2);
        frame.add(icon3);
        frame.add(button1);
        frame.add(button2);
        frame.add(button3);
        frame.add(button4);
        
        
        playMusic("bgsong1.wav");
        frame.setVisible(true);
    }

    public static void main(String[] args) {
        new Jon();
    }
}

class Jon1{
    
    private Clip clip; // Add a field to store the Clip object

    private void playMusic(String musicFile) {
        try {
            AudioInputStream audioInputStream = AudioSystem.getAudioInputStream(new File(musicFile).getAbsoluteFile());
            clip = AudioSystem.getClip(); // Store the Clip object in the field
            clip.open(audioInputStream);
            clip.start();
            clip.loop(Clip.LOOP_CONTINUOUSLY);
        } catch (UnsupportedAudioFileException | IOException ex) {
            ex.printStackTrace();
        } catch (LineUnavailableException ex) {
            ex.printStackTrace();
        }
    }

    private void stopMusic() {
        if (clip != null && clip.isRunning()) {
            clip.stop();
        }
    }

    Jon1() {
        JFrame frame = new JFrame();
        JPanelWithBackground panel = null;

        try {
            panel = new JPanelWithBackground("BG2.png");
        } catch (IOException e) {
            e.printStackTrace();
            return;
        }

        frame.setContentPane(panel);
        frame.setTitle("Mirangelica");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setSize(720, 420);
        frame.setLayout(null);
        frame.setResizable(false);
        
        frame.setLocationRelativeTo(null);

        Container c = frame.getContentPane(); //Gets the content layer


        // Labels

        JLabel label1 = new JLabel("");
        label1.setText("<html> <body> Greetings, adventurer. My name is Lucifer <br> welcome to the Town of Fitonia. </body> </html>");
        label1.setForeground(new Color(255, 255, 255));
        label1.setBounds(216, 260, 5000, 50);
        label1.setFont(new Font("Monospaced",Font.PLAIN, 12));

        JLabel label2 = new JLabel("");
        label2.setText("<html> <body> To stay in this town, you must complete <br> a set of challenges. My partner Mira will<br>show you </body> </html>");
        label2.setForeground(new Color(255, 255, 255));
        label2.setBounds(216, 263, 5000, 50);
        label2.setFont(new Font("Monospaced",Font.PLAIN, 12));
        label2.setVisible(false);


        JLabel label3 = new JLabel("");
        label3.setText("<html> <body> <br><center>CHALLENGES</center><br>1. Every 10 minutes: Posture Check.<br><br> 2. Every 15 minutes: Hydrate! Take a sip<br>of water. <br><br> 3. Every 20 minutes: Stretch your body. <br><br> 4. Every 30 minutes take 10-minute break <br> from the computer and move your body<br>around. </body> </html>");
        label3.setForeground(new Color(255, 255, 255));
        label3.setBounds(216, -2, 5000, 250);
        label3.setFont(new Font("Monospaced",Font.PLAIN, 12));
        label3.setVisible(false);

        JLabel label4 = new JLabel("");
        label4.setText("<html> <body> Greetings, my name is Mira. These are the<br>challenges. Do you dare to accept? </body> </html>");
        label4.setForeground(new Color(255, 255, 255));
        label4.setBounds(217, 260, 5000, 50);
        label4.setFont(new Font("Monospaced",Font.PLAIN, 12));
        label4.setVisible(false);

        JLabel label5 = new JLabel("");
        label5.setText("");
        label5.setForeground(new Color(255, 255, 255));
        label5.setBounds(220, 260, 5000, 50);
        label5.setFont(new Font("Monospaced",Font.PLAIN, 12));
        label5.setVisible(false);
    

        // Icons

        ImageIcon image = new ImageIcon("miralogo.png");
        frame.setIconImage(image.getImage());

        JLabel icon1 = new JLabel(); 
        icon1.setIcon(new ImageIcon("dia.png"));                // Dialouge box
        Dimension size1 = icon1.getPreferredSize(); 
        icon1.setBounds(200, 250, size1.width, size1.height);
        icon1.setOpaque(false);

        JLabel icon2 = new JLabel(); //JLabel Creation
        icon2.setIcon(new ImageIcon("angehalf.png"));              // Angelo halfbody
        Dimension size2 = icon2.getPreferredSize(); 
        icon2.setBounds(-20, 120, size2.width, size2.height);


        JLabel icon3 = new JLabel();
        icon3.setIcon(new ImageIcon("dia5.png"));               // Dialouge box Long Version LEFT
        Dimension size3 = icon3.getPreferredSize(); 
        icon3.setBounds(200, 20, size3.width, size3.height);
        icon3.setVisible(false);


        JLabel icon4 = new JLabel(); //JLabel Creation
        icon4.setIcon(new ImageIcon("mira5 .png"));              // Mira halfbody
        Dimension size6 = icon4.getPreferredSize(); 
        icon4.setBounds(10, 190, size6.width, size6.height);
        icon4.setVisible(false);


        //Timer
        
        //Button


        Icon B_icon3 = new ImageIcon("acc1.PNG");                     // Challenge Accepted
        JButton button3 = new JButton(B_icon3);
        int button3Width = 113;
        int button3Height = 33;
        int button3X = 283;
        int button3Y = 300;

        button3.setFont(new Font("Monospaced", Font.CENTER_BASELINE, 10)); 
        button3.setBounds(button3X, button3Y, button3Width, button3Height);
        button3.setVisible(false);
        


        Icon B_icon = new ImageIcon("continue.PNG");                   // Continue
        JButton button1 = new JButton(B_icon);
        int button1Width = 113;
        int button1Height = 33;
        int button1X = 550;
        int button1Y = 253;

        
        button1.setBounds(button1X, button1Y, button1Width, button1Height);
        button1.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e)
            {
                if(label1.isShowing()) {
                    label1.setVisible(false);
                    label2.setVisible(true);


                } else if(label2.isShowing()) {
                    icon1.setVisible(true);
                    icon2.setVisible(false);
                    icon4.setVisible(true);
                    icon3.setVisible(false);
                    label2.setVisible(false);
                    label3.setVisible(true);
                    label4.setVisible(true);
                    icon3.setVisible(true);
                    

                } else if(label3.isShowing() && label4.isShowing()){
                    label3.setVisible(true);
                    label4.setVisible(false);
                    label5.setVisible(true);
                    button1.setVisible(false);
                    button3.setVisible(true);
                    icon1.setVisible(false);
                    icon4.setVisible(true);

                }
            }
        });


        TrayIcon trayIcon = new TrayIcon(Toolkit.getDefaultToolkit().getImage("miratray.png"));
        trayIcon.setToolTip("Running");

        Runtime.getRuntime().addShutdownHook(new Thread() {
            public void run() {
                try {
                    SystemTray.getSystemTray().remove(trayIcon);
                } catch (Exception e) {
                    System.out.println(e);
                }
            }
        });


        try{
            SystemTray.getSystemTray().add(trayIcon);
        }catch(Exception e){
            System.out.println(e);
        }
    

        Icon B_icon2 = new ImageIcon("back1.PNG");                       // Back 
        JButton button2 = new JButton(B_icon2);
        int button2Width = 113;
        int button2Height = 33;
        int button2X = 550;
        int button2Y = 300;

        
        button2.setBounds(button2X, button2Y, button2Width, button2Height);
        button2.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e)
            {
                
                if(label1.isShowing()) {
                    new Write("Returned to main menu");
                    SystemTray.getSystemTray().remove(trayIcon);
                    stopMusic();
                    frame.setVisible(false);
                    new Jon();


                } else if(label2.isShowing()) {
                    icon1.setVisible(true);
                    icon2.setVisible(true);
                    icon4.setVisible(false);;
                    label2.setVisible(false);
                    label1.setVisible(true);
                    
                } else if(label3.isShowing() && !label5.isShowing()){
                    label4.setVisible(false);
                    label3.setVisible(false);
                    icon1.setVisible(true);
                    icon2.setVisible(true);
                    icon4.setVisible(false);
                    icon3.setVisible(false);;
                   
                
                    label2.setVisible(true);
                    button1.setVisible(true);
                    button3.setVisible(false);
                
                
                } else if(label5.isShowing()){
                    label3.setVisible(true);
                    label4.setVisible(true);
                    label5.setVisible(false);
                    icon1.setVisible(true);
                    icon4.setVisible(true);
                    icon2.setVisible(false);
                    button1.setVisible(true);
                    button3.setVisible(false);

                }
            }
        });

  

        // FUnction of timer

        ActionListener show_challenge_accepted = new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                stopMusic();
                frame.dispose();
                new Write("Challenge 1 started\n");
                new Challenge(5, "posture");
            }
        };

        ActionListener hideTrayFunc = new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                SystemTray.getSystemTray().remove(trayIcon);
            }
        };

        int milliseconds = 1000;
        int seconds = 1 * milliseconds;
        int minutes = 60 * seconds;

        Timer showTimer = new Timer(10 * minutes, show_challenge_accepted);
        showTimer.setRepeats(false);

        Timer hideTray = new Timer(10 * minutes, hideTrayFunc);
        hideTray.setRepeats(false);


        // Add ActionListener to button3 to start the timer


        button3.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                new Write("Challenge has been accepted");
                stopMusic();
                frame.setVisible(false);
                hideTray.start();
                showTimer.start();
            }
        });

        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent ae) {
                new Write("Exited the program\n");
                stopMusic();
            }
        });
       
        
        trayIcon.addMouseListener(new MouseAdapter() {
            @Override
            public void mouseClicked(MouseEvent e) {
                super.mouseClicked(e);
                if (frame.isShowing()) {
                    frame.setAlwaysOnTop(true); 
                } else {
                    new Write("Challenge 1 was executed early");
                    showTimer.stop();
                    frame.dispose();
                    SystemTray.getSystemTray().remove(trayIcon);
                    new Challenge(5, "posture");
                }
            }
        });

        Icon B_icon5 = new ImageIcon("unmute.PNG");
        JButton button4 = new JButton(B_icon5);
        int button4Width = 40;
        int button4Height = 34;
        int button4X = 650;
        int button4Y = 10;

        Icon B_icon6 = new ImageIcon("mute.PNG");
        JButton button5 = new JButton(B_icon6);
        int button5Width = 40;
        int button5Height = 34;
        int button5X = 650;
        int button5Y = 10;

        


        button4.setBounds(button4X, button4Y, button4Width, button4Height);
        button4.addActionListener(new ActionListener() {
        public void actionPerformed(ActionEvent ae) {
            stopMusic();
            new Write("Program has been muted");
            button4.setVisible(false); // Hide mute button
            button5.setVisible(true);  // Show unmute button
        }
    });

        button5.setBounds(button5X, button5Y, button5Width, button5Height);
        button5.addActionListener(new ActionListener() {
        public void actionPerformed(ActionEvent ae) {
            playMusic("bgsong2.wav");
            new Write("Program has been unmuted");
            button5.setVisible(false); // Hide unmute button
            button4.setVisible(true);  // Show mute button
        }
    });



         // Adds

        c.add(label1);
        c.add(label2);
        c.add(label3);
        c.add(label4);
        c.add(label5);
        c.add(icon1);
        c.add(icon2);
        c.add(icon3);
        c.add(icon4);
        c.add(button1);
        c.add(button2);
        c.add(button3);
        c.add(button4);
        c.add(button5);
        frame.setVisible(true); 


        playMusic("bgsong2.wav");
        

    }

    
}


class Challenge {


    private Clip clip; // Add a field to store the Clip object

    private void playMusic(String musicFile) {
        try {
            AudioInputStream audioInputStream = AudioSystem.getAudioInputStream(new File(musicFile).getAbsoluteFile());
            clip = AudioSystem.getClip(); // Store the Clip object in the field
            clip.open(audioInputStream);
            clip.start();
            clip.loop(Clip.LOOP_CONTINUOUSLY);
        } catch (UnsupportedAudioFileException | IOException ex) {
            ex.printStackTrace();
        } catch (LineUnavailableException ex) {
            ex.printStackTrace();
        }
    }

    private void stopMusic() {
        if (clip != null && clip.isRunning()) {
            clip.stop();
        }
    }
    
    Challenge(int time, String name) {
        
        // Frame making

        JFrame frame = new JFrame();
        JPanelWithBackground panel = null;

        try {

            if(name == "posture"){

                panel = new JPanelWithBackground("BG3.png");
            }
            else if(name == "hydrate"){
                panel = new JPanelWithBackground("BG4.png");
            }
            else if(name == "stretch"){
                panel = new JPanelWithBackground("BG5.png");
            }
            else if(name == "break"){
                panel = new JPanelWithBackground("BG6.png");
            }


        } catch (IOException e) {
            e.printStackTrace();
            return;
        }

        frame.setContentPane(panel);
        frame.setTitle("Mirangela");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setSize(720, 420);
        frame.setLayout(null);
        frame.setResizable(false);
        frame.setLocationRelativeTo(null);
        Container c = frame.getContentPane(); //Gets the content layer


        ImageIcon image = new ImageIcon("miralogo.png");
        frame.setIconImage(image.getImage());

        Icon B_icon = new ImageIcon("completed.PNG");
        JButton button4 = new JButton(B_icon);
        int button4Width = 113;
        int button4Height = 33;
        int button4X = 550;
        int button4Y = 253;

        

       
       // start a new challenge after challenge completed is clicked

        ActionListener show_advance_progress = new ActionListener() {            // start a new challenge after challenge completed is clicked
            public void actionPerformed(ActionEvent evt) {
                if(name == "posture") {
                    new Write("Challenge 1 has executed in time");
                    new Write("Challenge 2 has begun");
                    stopMusic();
                    frame.setVisible(false);    
                    new Challenge(5, "hydrate"); 
                } else if(name == "hydrate") {
                    new Write("Challenge 2 has executed in time");
                    new Write("Challenge 3 has begun");
                    stopMusic();
                    frame.setVisible(false);    
                    new Challenge(10, "stretch");
                } else if(name == "stretch") {
                    new Write("Challenge 3 has executed in time");
                    new Write("Challenge 4 has begun");
                    stopMusic();
                    frame.setVisible(false);    
                    new Challenge(10, "break");
                } else if(name == "break") {
                    new Write("Challenge 4 has executed in time");
                    new Write("Challenge 1 has begun");
                    stopMusic();
                    frame.setVisible(false);    
                    new Challenge(5, "posture");
                }
            }
        };

        TrayIcon trayIcon = new TrayIcon(Toolkit.getDefaultToolkit().getImage("miratray.png"));
        trayIcon.setToolTip("Running");

        Runtime.getRuntime().addShutdownHook(new Thread() {
            public void run() {
                try {
                    SystemTray.getSystemTray().remove(trayIcon);
                } catch (Exception e) {
                    System.out.println(e);
                }
            }
        });

        try{
            SystemTray.getSystemTray().add(trayIcon);
        }catch(Exception e){
            System.out.println(e);
        }

         // time function

        ActionListener hideTrayFunc = new ActionListener() {                        // time function
            public void actionPerformed(ActionEvent evt) {
                SystemTray.getSystemTray().remove(trayIcon);
            }
        };

        int milliseconds = 1000;
        int seconds = 1 * milliseconds;
        int minutes = 60 * seconds;

        Timer showTimer = new Timer(time * minutes, show_advance_progress);
        showTimer.setRepeats(false);

        Timer hideTray = new Timer(time * minutes, hideTrayFunc);
        hideTray.setRepeats(false);


        // Challenge completed button
                                                                    
        button4.setBounds(button4X, button4Y, button4Width, button4Height);                            // Challenge completed button
        button4.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent ae) {
                if(name == "posture") {
                    new Write("Challenge 1 has been completed");
                } else if(name == "hydrate") {
                    new Write("Challenge 2 has been completed");
                } else if(name == "stretch") {
                    new Write("Challenge 3 has been completed");
                } else if(name == "break") {
                    new Write("Challenge 4 has been completed");
                }
                stopMusic();
                hideTray.start();
                showTimer.start();
                frame.setVisible(false);
            }
        });
        button4.setVisible(true);

        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent ae) {
                new Write("Exited the program\n");
                stopMusic();
            }
        });
       
            // Storage of files for new functions 

            // Labels

            JLabel label1 = new JLabel("");                                                      // Labels


            // Icons

            JLabel icon1 = new JLabel();                                                               // Icons
            JLabel icon2 = new JLabel();
            JLabel icon3 = new JLabel();
            JLabel gif1 = new JLabel();
            JLabel gif2 = new JLabel();
            JLabel gif3 = new JLabel();
            JLabel gif4 = new JLabel();
            JLabel gif5 = new JLabel();
            JLabel gif6 = new JLabel();
            JLabel gif7 = new JLabel();
            JLabel gif8 = new JLabel();
            JLabel gif9 = new JLabel();
            JLabel gif10 = new JLabel();
            JLabel gif11 = new JLabel();
            JLabel gif12 = new JLabel();
            JLabel gif13 = new JLabel();
            
            // SYstem tray add
    
            
            
            //button Icon

            Icon B_icon2 = new ImageIcon("giveup.PNG");                                       //button Icon


            //buttons and variable
            JButton button5 = new JButton(B_icon2);                                                   //buttons and variable
            int button5Width = 113;
            int button5Height = 33;
            int button5X = 550;
            int button5Y = 300;
        
            button5.setBounds(button5X, button5Y, button5Width, button5Height);

            


            trayIcon.addMouseListener(new MouseAdapter() {
                @Override
                public void mouseClicked(MouseEvent e) {
                    super.mouseClicked(e);
                    if (frame.isShowing()) {
                        frame.setAlwaysOnTop(true); 
                    } else {
                        showTimer.stop();
                        if(name == "posture") {
                            new Write("Challenge 2 was executed early");
                            new Write("Challenge 2 has begun");
                            SystemTray.getSystemTray().remove(trayIcon);
                            new Challenge(10, "hydrate"); 
                        } else if(name == "hydrate") {   
                            new Write("Challenge 3 was executed early");
                            new Write("Challenge 3 has begun");
                            SystemTray.getSystemTray().remove(trayIcon);
                            new Challenge(10, "stretch");
                        } else if(name == "stretch") {    
                            new Write("Challenge 4 was executed early");
                            new Write("Challenge 4 has begun");
                            SystemTray.getSystemTray().remove(trayIcon);
                            new Challenge(10 , "break");
                        } else if(name == "break") {    
                            new Write("Challenge 1 was executed early");
                            new Write("Challenge 1 has begun");
                            SystemTray.getSystemTray().remove(trayIcon);
                            new Challenge(10, "posture");
                        }
                    }
                }
            });
    

            // Challenge 1 Posture Check

            if(name == "posture") {

                label1.setText("<html> <body> Proper computer posture prevents pain and<br>fatigue, boosting comfort and productivity.<br> It also enhances circulation and cognitive<br>function. </body> </html>");                       // Label
                label1.setForeground(new Color(255, 255, 255));
                label1.setBounds(210, 253, 5000, 70);
                label1.setFont(new Font("Monospaced",Font.PLAIN, 12));
                label1.setVisible(true);

                icon1.setIcon(new ImageIcon("dia.png"));                  // Dialouge box
                Dimension size1 = icon1.getPreferredSize(); 
                icon1.setBounds(200, 250, size1.width, size1.height);
                icon1.setOpaque(false);
            
                icon2.setIcon(new ImageIcon("mira2.png"));                // Icon 1
                Dimension size2 = icon2.getPreferredSize(); 
                icon2.setBounds(5, 150, size2.width, size2.height);
                icon2.setOpaque(false);
                icon2.setVisible(true);

                icon3.setIcon(new ImageIcon("pos2.png"));                // Icon 2
                Dimension size5 = icon3.getPreferredSize(); 
                icon3.setBounds(70, 50, size5.width, size5.height);
                icon3.setOpaque(false);
                icon3.setVisible(true);

                gif1.setIcon(new ImageIcon("10minute.gif"));                // 10 minute GIF 1
                Dimension size3 = gif1.getPreferredSize(); 
                gif1.setBounds(310, 30, size3.width, size3.height);
                gif1.setOpaque(false);
                gif1.setVisible(true);


                gif2.setIcon(new ImageIcon("10minutes2.gif"));                // 10 minute GIF 2
                Dimension size4 = gif2.getPreferredSize(); 
                gif2.setBounds(485, 50, size4.width, size4.height);
                gif2.setOpaque(false);
                gif2.setVisible(true);

           // Challenge 2 Hydrate

            } else if(name == "hydrate") {

                label1.setText("<html> <body> Hydration keeps your body functioning<br>well, helps digestion, and boosts physical<br>and mental performance </body> </html>");               // Label
                label1.setForeground(new Color(255, 255, 255));
                label1.setBounds(213, 263, 5000, 50);
                label1.setFont(new Font("Monospaced",Font.PLAIN, 12));
                label1.setVisible(true);

                
                icon1.setIcon(new ImageIcon("dia.png"));                   // Dialouge box
                Dimension size1 = icon1.getPreferredSize(); 
                icon1.setBounds(200, 250, size1.width, size1.height);
                icon1.setOpaque(false);

                icon2.setIcon(new ImageIcon("mira2.png"));                // Icon
                Dimension size2 = icon2.getPreferredSize(); 
                icon2.setBounds(5, 150, size2.width, size2.height);
                icon2.setOpaque(false);
                icon2.setVisible(true);

                gif3.setIcon(new ImageIcon("15minutes.gif"));                // 15 minute GIF 1
                Dimension size3 = gif3.getPreferredSize(); 
                gif3.setBounds(75, 50, size3.width, size3.height);
                gif3.setOpaque(false);
                gif3.setVisible(true);

                gif4.setIcon(new ImageIcon("15minutes2.gif"));                // 15 minute GIF 2
                Dimension size4 = gif4.getPreferredSize(); 
                gif4.setBounds(380, 50, size4.width, size4.height);
                gif4.setOpaque(false);
                gif4.setVisible(true);



           // Challenge 3 Stretch

            } else if(name == "stretch") {

                label1.setText("<html> <body> Stretching at the computer helps prevent<br>stiffness and injuries, keeping you<br>flexibleand healthy.  </body> </html>");              //Label
                label1.setForeground(new Color(255, 255, 255)); 
                label1.setBounds(218, 262, 5000, 50);
                label1.setFont(new Font("Monospaced",Font.PLAIN, 12));
                label1.setVisible(true);

               
                icon1.setIcon(new ImageIcon("dia.png"));                // Dialouge box
                Dimension size1 = icon1.getPreferredSize(); 
                icon1.setBounds(200, 250, size1.width, size1.height);
                icon1.setOpaque(false);

                icon2.setIcon(new ImageIcon("mira2.png"));                // Icon
                Dimension size2 = icon2.getPreferredSize(); 
                icon2.setBounds(5, 150, size2.width, size2.height);
                icon2.setOpaque(false);
                icon2.setVisible(true);

                gif5.setIcon(new ImageIcon("20m.gif"));                // 20 minute GIF 1
                Dimension size3 = gif5.getPreferredSize(); 
                gif5.setBounds(35, 60, size3.width, size3.height);
                gif5.setOpaque(false);
                gif5.setVisible(true);

                gif6.setIcon(new ImageIcon("20m1.gif"));                // 20 minute GIF 2
                Dimension size4 = gif6.getPreferredSize(); 
                gif6.setBounds(195, 60, size4.width, size4.height);
                gif6.setOpaque(false);
                gif6.setVisible(true);

                gif7.setIcon(new ImageIcon("20m2.gif"));                // 20 minute GIF 3
                Dimension size5 = gif7.getPreferredSize(); 
                gif7.setBounds(355, 60, size5.width, size5.height);
                gif7.setOpaque(false);
                gif7.setVisible(true);

                gif8.setIcon(new ImageIcon("20m3.gif"));                // 20 minute GIF 4
                Dimension size6 = gif8.getPreferredSize(); 
                gif8.setBounds(515, 60, size6.width, size6.height);
                gif8.setOpaque(false);
                gif8.setVisible(true);

                // Challenge 4 Break    

                } else if(name == "break") {

                label1.setText("<html> <body> Resting for 10 minutes after 30 minutes<br>of computer use prevents strain, refreshes<br>your mind, and boosts productivity </body> </html>");             // Label
                label1.setForeground(new Color(255, 255, 255));
                label1.setBounds(214, 262, 5000, 50);
                label1.setFont(new Font("Monospaced",Font.PLAIN, 12));
                label1.setVisible(true);

                icon1.setIcon(new ImageIcon("dia.png"));                // Dialouge box
                Dimension size1 = icon1.getPreferredSize(); 
                icon1.setBounds(200, 250, size1.width, size1.height);
                icon1.setOpaque(false);

                icon2.setIcon(new ImageIcon("mira2.png"));                // Challenge 4 Break
                Dimension size2 = icon2.getPreferredSize(); 
                icon2.setBounds(5, 150, size2.width, size2.height);
                icon2.setOpaque(false);
                icon2.setVisible(true);


                gif5.setIcon(new ImageIcon("30m1.gif"));                // 30 minute GIF 1
                Dimension size3 = gif5.getPreferredSize(); 
                gif5.setBounds(35, 60, size3.width, size3.height);
                gif5.setOpaque(false);
                gif5.setVisible(true);

                gif6.setIcon(new ImageIcon("30m2.gif"));                // 30 minute GIF 2
                Dimension size4 = gif6.getPreferredSize(); 
                gif6.setBounds(195, 60, size4.width, size4.height);
                gif6.setOpaque(false);
                gif6.setVisible(true);

                gif7.setIcon(new ImageIcon("30m3.gif"));                // 30 minute GIF 3
                Dimension size5 = gif7.getPreferredSize(); 
                gif7.setBounds(355, 60, size5.width, size5.height);
                gif7.setOpaque(false);
                gif7.setVisible(true);

                gif8.setIcon(new ImageIcon("30m4.gif"));                // 30 minute GIF 4
                Dimension size6 = gif8.getPreferredSize(); 
                gif8.setBounds(515, 60, size6.width, size6.height);
                gif8.setOpaque(false);
                gif8.setVisible(true);

                }

                button5.addActionListener(new ActionListener() {
                    public void actionPerformed(ActionEvent ae) {
                        new Write("Exited the program\n");
                        stopMusic();
                        frame.dispose();
                        SystemTray.getSystemTray().remove(trayIcon);
                        System.exit(0);
                    }
                });
            


                Icon B_icon3 = new ImageIcon("unmute.PNG");
                JButton button6 = new JButton(B_icon3);
                int button6Width = 40;
                int button6Height = 34;
                int button6X = 650;
                int button6Y = 10;
        
                Icon B_icon4 = new ImageIcon("mute.PNG");
                JButton button7 = new JButton(B_icon4);
                int button7Width = 40;
                int button7Height = 34;
                int button7X = 650;
                int button7Y = 10;
        
                
        
        
                button6.setBounds(button6X, button6Y, button6Width, button6Height);
                button6.addActionListener(new ActionListener() {
                public void actionPerformed(ActionEvent ae) {
                    stopMusic();
                    new Write("Program has been muted");
                    button6.setVisible(false); // Hide mute button
                    button7.setVisible(true);  // Show unmute button
                }
            });
        
                button7.setBounds(button7X, button7Y, button7Width, button7Height);
                button7.addActionListener(new ActionListener() {
                public void actionPerformed(ActionEvent ae) {
                    playMusic("bgsong3.wav");
                    new Write("Program has been unmuted");
                    button6.setVisible(true); // Hide unmute button
                    button7.setVisible(false);  // Show mute button
                }
            });
    
                
                
        // add in frame
        c.add(label1);
        c.add(icon1);
        c.add(icon2);
        c.add(icon3);
        c.add(gif1);
        c.add(gif2);
        c.add(gif3);
        c.add(gif4);
        c.add(gif5);
        c.add(gif6);
        c.add(gif7);
        c.add(gif8);
        c.add(gif9);
        c.add(gif10);
        c.add(gif11);
        c.add(gif12);
        c.add(gif13);
        c.add(button4);
        c.add(button5);
        c.add(button6);
        c.add(button7);
        frame.setVisible(true);

        playMusic("bgsong3.wav");
    }
}