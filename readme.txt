=== Vidsy.tv video gallery and video CMS ===
Contributors: tcattitude, ramirotenorio
Tags: video, gallery, youtube, vimeo, ustream, embed, player, playlist, integration, tv, shortcode, widget
Requires at least: 3.5.1
Tested up to: 3.8.1
Stable tag: 1.0.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Customize video galleries, enrich your entire website with synced video widgets and manage everything from your dashboard!

== Description ==

[Vidsy.tv](http://vidsy.tv/ "The easiest way to manage videos on your website") is a free video CMS, a simple tool that helps you manage videos on your website and create custom video galleries. Keep an organized video page with all the videos you collect for your audience, maintain video galleries on different pages or sections of your website and do all of this in seconds and without having to change any line of code on your site.

Organize videos from Youtube, Vimeo or Ustream into video playlists, add your own title and descriptions and add this playlists to your posts, galleries, pages, sidebars or footers simply by using the widgets in your dashboard.

Some cool features that you get:

- Instantly create video playlists.
- Add your own metadata to your playlists and videos.
- Easily add your video playlists to any WordPress menu, page, post or sidebar.
- Customize your main video page and keep it automatically updated with all the new videos you collect on your playlists.
- Customize your widgets.
- Follow Youtube, Vimeo and Ustream accounts directly form your dashboard without having to keep independent accounts: specially useful for community managers.
- Use our Bookmarklet to add videos to your WP directly from the source, without having to open html or change your code.
- Connect your Facebook and Twitter accounts and get directly on your dashboard all the videos that are shared on your social networks.

Check out some in action demos here:

- [Player with videos from single Playlist](http://vidsy.tv/blog/videos/player-from-playlist/ "Demo playerplaylist")
- [Player with recent videos](http://vidsy.tv/blog/videos/player-with-recent-videos/ "Demo playerrecent")
- [Fullsite embed](http://vidsy.tv/blog/videos/ "Demo fullsite")

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Appearance -> Widgets and add Vidsy's Widget to your sidebar (configure it as you like)
4. Use our shortcode to display your full-site gallery inside any page or post: `[vidsy width="100%" height="800px" theme="light"]` (Note: `theme` flag can be: `light` or `dark`)
5. Use our shortcode to display a player with your recent videos: `[vidsy type="playerrecent" width="100%" height="430px" theme="dark"]`
6. Use our shortcode to display a player with videos from a single playlist: `[vidsy type="playerplaylist" playlist="name of the playlist" width="100%" height="430px" theme="light"]`

== Frequently Asked Questions ==

= Will there be new features and integration of the Vidsy.tv service with WordPress? =

Yes, we are working to bring more Vidsy's Widgets into WordPress directly to make your life easier.

= What types of videos can I collect and share on my website? =
YouTube, Vimeo and Ustream videos (for the moment).

= How can I add playlists to my website =
Just go to your Vidsy's dashboard and you will find a complete section of Widgets, choose the widget you need, customize it and copy-paste the code on the html of the area you want (page, sidebar, footer, etc).

= Can I customize a video page with all my playlists? =
Yeah, you have different themes to choose from, and you can customize them to look like your website.

= Can I change the URL of my video page for my own URL? =
Oh yeah! go to your settings and change the URL (you will need to create a subdomain on your side and point it to your Vidsy.tv account)

= How do I add videos to my video gallery? =
1. Use the search engine on your dashboard to find videos on Youtube, Vimeo or Ustream by keyword,  then click on collect.
1. Search by url on the search engine.
1. Use the bookmarklet whenever you are browsing the web.

= I am a community manager, can I follow videos on Youtube, Vimeo or Ustream accounts from my dashboard? =
Yep, you can follow those accounts, each time there is a new video uploaded to a certain account it will show on your timeline.

= Can I connect my Facebook and Twitter accounts and get on my timeline new videos that are shared on my social networks? =
Yes you can!

= Can I send you guys an email with ideas/comments/requests? =
Please do, shoot us an email to hello@vidsy.tv

== Screenshots ==

1. Widget configuration.
2. Recent Videos, available as Widget and Shortcode (Light variant, Dark also included).
3. Full-site video gallery using `[vidsy]` shortcode.
4. Vidsy's admin panel directly in your WordPress Dashboard.
5. Player with Recent Videos, available as Widget and Shortcode (Light variant, Dark also included).
6. Player with videos from a single Playlist, Widget configuration.
7. Player with videos from a single Playlist, public view.

== Changelog ==

= 1.0.4.0 =
* Resolved a PHP Warning on admin settings screen (Thanks to Camila Ramirez for the report!)
* New Widget available: Player with videos from a single Playlist.
* New Shortcode type flag available, to embed a full Player with videos from a single Playlist to any post/page: `[vidsy type="playerplaylist" playlist="name of the playlist" width="100%" height="430px" theme="light"]`
* Check out a demo of the new shortcode flag (playerplaylist) in action [here](http://vidsy.tv/blog/videos/player-from-playlist/ "Demo playerplaylist")

= 1.0.3.0 =
* New Widget available: Player with your Recent Videos.
* New Shortcode flag (type) let you embed a full Player with your Recent videos at any page/post: `[vidsy type="playerrecent" width="100%" height="430px" theme="dark"]`
* Check out a demo of the shortcode (with the flag: playerrecent) in action [here](http://vidsy.tv/blog/videos/player-with-recent-videos/ "Demo playerrecent")

= 1.0.2 =
* Added banner image (at plugin page) and readme file corrected. So sorry for the updates!.

= 1.0.1 =
* New options page. Set your account once, all Widgets will sync automatically with your settings.
* Access your Vidsy.tv dashboard directly from your WordPress dashboard.
* New shortcode to integrate a full-site video gallery to any WordPress page. Give it a try!: `[vidsy width="100%" height="800px"]` (*) width/height optional, in pixels or percentage.
Width defaults to 100%, height to 800 pixels.
If you want a dark one, set the flag theme like this: `[vidsy width="100%" height="800px" theme="dark"]`

= 1.0.0 =
* First released version.