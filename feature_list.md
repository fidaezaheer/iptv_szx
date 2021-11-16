# IPTV Admin System Features

The features of IPTV Admin System. 
http://szx.myiptv.cloud/admin/index.php?lang=1

1. Support Super Administrator and  Agent(to Resell) Administrator managment Mode to login.
2. Support Enable or Disable to setup Authorization Actions
3. Device(terminal) Startup option remote config
*  Boot on EPG Home(page)
*  Boot start on broadcast interface(page)
*  Boot start on on-demand interface(page)
*  Boot start on playback interface(page)
4. Operation Log record
5. One button to  Backup or restore Database(save all of the settings to SQL file)
6. Device(Terminal) language,  time zone setup and background image setting
7. Two type of Live Panel Renderings for options
8. Channel Category Editor and setup password to lock median contents,for each channel can setup
* enable/diasble
* SN
* Icon img
* name
* url
* catagory
* actions(edit/delete)

10. Terminal(APK)  vesion remote Management Upgrade Mode(Forced /Hint/ Force all agents to upgrade) 
11. Authorization Management (base on MAC Address)
* Off/ Suspend/ Permanent or Authorization  Remaining Date
* Playlist Allocation Method: All Channels Allocated or Automatically assigning lists based on your region or Manually select list
12. Encrypt the data between apk and backend by openssl_encrypt($data)
13. Device Login IP address monitor (Maxim ip login account allowed configable)
