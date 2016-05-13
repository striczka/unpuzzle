@extends('frontend.layout')

@section('seo')
    <title>{{ 'Сервис' }}</title>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
@endsection

@section('content')
<section class="breadcrumbs">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="index.html">Home</a></li>
                <li class="active">Сервис</li>
            </ol>
        </div>
    </div>
</section>

<section class="content">
    <!--Simple Menu-->
    <div class="container">
        <div class="row">
            <div class="col s12 text-page no-padding">
                <h3 class="staticPage"><span>how it work</span></h3>
            </div>
        </div>
    </div>
    <div class="container">
        <div class='faq'>
            <div class="row">
                <div class='col s12 m5 l5 no-padding'>
                    <div class="video">
                        <video controls="true" height="100%" width="100%">
                            <source src="/video/howtoplay.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
                <div class='col s12 m7 l7 no-padding'>
                    <h4 class="faqH4">FAQ (FREQUENTLY ASKED QUESTIONS)</h4>
                    <ul>
                        <li>
                            <a date-qFaq='1' onclick="faq(this);">1. I`ve bought the quest, what's next?</a>
                            <div date-faq="1">You will get all the instructions and the activation code by email. Activate the game when you arrive to the starting point indicated in the e-mail. It is automatically generated and you will receive it a few minutes after the purchase. If you haven`t got the email, please write or call us before the game.</div>
                        </li>
                        <li>
                            <a date-qFaq='2' onclick="faq(this);">2. How much time do I need to complete the route?</a>
                            <div date-faq="2">There are different ways of completing the game - you can go at your own pace, discovering the city without rush and making picture or lunch breaks. In this case it will take you longer than 2 hours. But the time is unlimited, so you can play as long as you want, and if you don't finish the quest, you can do it the next day. The code will be active for 3 months since the day of purchase and for 1 month since you start the quest.
                            Another way is to buy the team competition option - in this case the faster you come to the finish - the better.</div>
                            </li>
                        <li>
                            <a date-qFaq='3' onclick="faq(this);">3. Do I need to download something? What kind of phone do I need? And if I run out of the battery?</a>
                            <div date-faq="3">You will get all the instructions and the activation code by email. Activate the game when you arrive to the starting point indicated in the e-mail. It is automatically generated and you will receive it a few minutes after the purchase. If you haven`t got the email, please write or call us before the game.</div>
                        </li>
                        <li>
                            <a date-qFaq='4' onclick="faq(this);">4. Will I still have access to the quest after I finish?</a>
                            <div date-faq="4">You don`t need to download anything, you will use a special online game platform. So the main thingyou need is the internet connection and enough energy at your phone. If for some reason you need to enter the game from another device, just repeat the procedure - go to the game website and enter the code - the quest will appear at the moment you left it.</div>
                        </li>
                        <li>
                            <a date-qFaq='5' onclick="faq(this);">5. Will I need a GPS to orient me?</a>
                            <div date-faq="5">Read carefully the questions and instruction where to go - all you need to find the place is already there!</div>
                        </li>
                        <li>
                            <a date-qFaq='6' onclick="faq(this);">6. Is there a quest app?</a>
                            <div date-faq="6">Not yet. But we want to launch an offline app soon.</div>
                        </li>
                        <li>
                            <a date-qFaq='7' onclick="faq(this);">7. What if I get lost &amp; can`t find the answer</a>
                            <div date-faq="7">For this case you can always use our help buttons. First ones give you useful hints, last one reveals the right answer that will help you continue the quest. Remember that we are also here to help you.</div>
                        </li>
                        <li>
                            <a date-qFaq='8' onclick="faq(this);">7. Can I do it by bike/ in-line skates?</a>
                            <div date-faq="8" >Most of the roads are suitable for bikes - but some of the central streets may be too crowded and not convenient to drive. In-line skates are not recommended as most of the streets are made of block-stone.</div>
                        </li>

                        <li>
                            <a date-qFaq='9' onclick="faq(this);">9. What if the object of the quest is not there any more?</a>
                            <div date-faq="9">We update our quests with the most recent info so you will always be able to find the right answer &amp; continue your quest! If there is something that just happened please call us, we will be very grateful to get the latest news from you and help you of course.</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="banerHIW">
        <div class="container">
            <div class="row">
                <div class="col s12 m4 l4 no-padding">
                    <div class="item">
                        <img src="/images/Location.png">
                        <h5>New places</h5>
                        <p>Visit cool locations,<br> completing quests</p>
                    </div>
                </div>
                <div class="col s12 m4 l4 no-padding">
                    <div class="item">
                        <img src="/images/Camera.png">
                        <h5>memorable moments</h5>
                        <p>Solve interesting puzzles,<br> make a funny photo</p>
                    </div>
                </div>
                <div class="col s12 m4 l4 no-padding">
                    <div class="item">
                        <img src="/images/logoBaner.png">
                        <h5>Very interesting</h5>
                        <p>Find out lots of interesting facts and<br> legends about the city!</p>
                    </div>
                </div>
            </div>
        </div>        
    </div> 
    <!--/Menu-->
</section>

<script type="text/javascript">
    function faq(thisQ){
        var t = thisQ;
        if($(t).attr('class') != 'active'){
            $('div[date-faq]').slideUp('slow');
            $('a[date-qfaq]').removeClass('active');
            $(t).next().slideDown('slow');
            $(t).addClass('active');
        }else{
           /* $(t).next().fadeOut(); */
            $(t).next().slideUp('slow');
            $(t).removeClass('active');
        }
    }
</script>

@endsection
