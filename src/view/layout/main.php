<?php

use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\Users\Roles;

?>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> GradHire | <?= $this->title ?></title>
    <link rel="stylesheet" href="/resources/css/input.css">
    <link rel="stylesheet" href="/resources/css/output.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/resources/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/resources/images/favicon-16x16.png">
</head>
<body>

<nav aria-label="Top" class="fixed z-20 bg-white w-full bg-opacity-90 backdrop-blur-xl backdrop-filter border-b">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 top-0 z-50">
        <div class="flex h-16 items-center">
            <button id="burger-btn" type="button" class="relative rounded-md bg-white p-2 text-zinc-400 lg:hidden">
                <span class="absolute -inset-0.5"></span>
                <span class="sr-only">Open menu</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                     aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>
            <div class="ml-4 flex lg:ml-0">
                <a href="/">
                    <span class="sr-only">GradHire</span>
                    <img class="h-8 w-auto" src="/resources/images/logo.png" alt="">
                </a>
            </div>

            <div class="hidden lg:ml-8 lg:block lg:self-stretch">
                <div class="flex h-full space-x-8">
                    <?php if (!Application::isGuest()): ?>
                        <?php if (!Auth::has_role(Roles::Enterprise)): ?>
                            <a href="/offres"
                               class="flex items-center text-sm font-medium text-zinc-700 hover:text-zinc-800">Offres</a>
                            <a href="/entreprises"
                               class="flex items-center text-sm font-medium text-zinc-700 hover:text-zinc-800">Entreprises</a>

                        <?php endif; ?>
                        <?php if (Auth::has_role(Roles::Enterprise)): ?>
                            <a href="/offres/create"
                               class="flex items-center text-sm font-medium text-zinc-700 hover:text-zinc-800">Créer une
                                offre</a>
                            <a href="/candidatures"
                               class="flex items-center text-sm font-medium text-zinc-700 hover:text-zinc-800">Candidatures</a>
                            <a href="/ListeTuteurPro"
                               class="flex items-center text-sm font-medium text-zinc-700 hover:text-zinc-800">Tuteurs</a>
                        <?php endif; ?>
                        <?php if (Auth::has_role(Roles::Manager, Roles::Staff)): ?>
                            <a href="/utilisateurs"
                               class="flex items-center text-sm font-medium text-zinc-700 hover:text-zinc-800">Utilisateurs</a>
                            <a href="/candidatures"
                               class="flex items-center text-sm font-medium text-zinc-700 hover:text-zinc-800">Candidatures</a>
                            <a href="/ListeTuteurPro"
                               class="flex items-center text-sm font-medium text-zinc-700 hover:text-zinc-800">Tuteurs</a>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
            </div>

            <div class="ml-auto flex items-center">
                <?php if (Application::isGuest()): ?>
                    <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6">
                        <a href="/login" class="text-sm font-medium text-zinc-700 hover:text-zinc-800">Se connecter</a>
                        <span class="h-6 w-px bg-zinc-200" aria-hidden="true"></span>
                        <a href="/register" class="text-sm font-medium text-zinc-700 hover:text-zinc-800">S'inscrire</a>
                    </div>
                <?php else: ?>
                    <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6">
                        <a class="flex flex-row gap-4 items-center justify-center text-sm font-medium text-zinc-700 hover:text-zinc-800"
                           href="<?= Auth::has_role(Roles::Enterprise) ? "/entreprises/" . Application::getUser()->id() : '/profile' ?>">
                            <?= Application::getUser()->full_name() ?>
                            <div class="rounded-full overflow-hidden h-7 w-7">
                                <img src="<?= Application::getUser()->get_picture() ?>" alt="Photo de profil"
                                     class="w-full h-full object-cover rounded-full"/>
                            </div>
                        </a>
                        <span class="h-6 w-px bg-zinc-200" aria-hidden="true"></span>
                        <a href="/logout" class="text-sm font-medium text-zinc-700 hover:text-zinc-800">Se
                            déconnecter</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<div id="nav-container"
     class="hidden fixed top-0 left-0 w-full h-screen bg-white bg-opacity-90 backdrop-blur-xl backdrop-filter z-50  mt-[65px]">
    <div class="flex flex-col justify-center items-center space-y-8 uppercase mt-[50px]">

        <?php if (!Application::isGuest()): ?>
            <?php if (!Auth::has_role(Roles::Enterprise)): ?>
                <a href="/offres"
                   class="flex items-center text-xl font-medium text-zinc-700 hover:text-zinc-800">Offres</a>
                <a href="/entreprises"
                   class="flex items-center text-xl font-medium text-zinc-700 hover:text-zinc-800">Entreprises</a>
            <?php endif; ?>
            <?php if (Auth::has_role(Roles::Enterprise)): ?>
                <a href="/offres/create"
                   class="flex items-center text-xl font-medium text-zinc-700 hover:text-zinc-800">Créer une
                    offre</a>
            <?php endif; ?>
            <?php if (Auth::has_role(Roles::Manager, Roles::Staff)): ?>
                <a href="/utilisateurs"
                   class="flex items-center text-xl font-medium text-zinc-700 hover:text-zinc-800">Utilisateurs</a>
                <a href="/candidatures"
                   class="flex items-center text-xl font-medium text-zinc-700 hover:text-zinc-800">Candidatures</a>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>
<div id="blur-background" class="hidden w-screen h-screen fixed z-50 top-0 left-0 backdrop-blur-md"></div>
<div class="w-full flex flex-col justify-center items-center">
    <div class="max-w-7xl w-full px-4 sm:px-6 lg:px-8 flex flex-col justify-center mt-[65px] items-center">
        {{content}}
        <footer aria-labelledby="footer-heading" class="bg-white w-full">
            <h2 id="footer-heading" class="sr-only">Footer</h2>
            <div class="mx-auto max-w-7xl ">
                <!--                <div class="py-20 xl:grid xl:grid-cols-3 xl:gap-8">
                                    <div class="grid grid-cols-2 gap-8 xl:col-span-2">
                                        <div class="space-y-16 md:grid md:grid-cols-2 md:gap-8 md:space-y-0">
                                            <div>
                                                <h3 class="text-sm font-medium text-zinc-900">Offres</h3>
                                                <ul role="list" class="mt-6 space-y-6">
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">
                                                    </li>
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-medium text-zinc-900">Entreprises</h3>
                                                <ul role="list" class="mt-6 space-y-6">
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="space-y-16 md:grid md:grid-cols-2 md:gap-8 md:space-y-0">
                                            <div>
                                                <h3 class="text-sm font-medium text-zinc-900">Profile</h3>
                                                <ul role="list" class="mt-6 space-y-6">
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-medium text-zinc-900">Social</h3>
                                                <ul role="list" class="mt-6 space-y-6">
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                    <li class="text-sm">
                                                        <a href="#" class="text-zinc-500 hover:text-zinc-600">Lorem</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-16 md:mt-16 xl:mt-0">
                                        <h3 class="text-sm font-medium text-zinc-900">S'inscrire à la newsletter</h3>
                                        <p class="mt-6 text-sm text-zinc-500">Les dernières nouvelles et offres</p>
                                        <form class="mt-2 flex sm:max-w-md">
                                            <label for="email-address" class="sr-only">Email address</label>
                                            <input id="email-address" type="text" autocomplete="email" required
                                                   class="w-full min-w-0 appearance-none rounded-md border border-zinc-300 bg-white px-4 py-2 text-base text-zinc-500 placeholder-zinc-500 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500">
                                            <div class="ml-4 flex-shrink-0">
                                                <button type="submit"
                                                        class="flex w-full items-center justify-center rounded-md border border-transparent bg-zinc-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2">
                                                    S'inscrire
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                -->
                <div class="border-t border-zinc-200 py-10">
                    <p class="text-sm text-zinc-500">Copyright &copy; 2023 -
                        <span class="text-zinc-900">GradHire</span>
                    </p>
                </div>
            </div>
        </footer>
    </div>
</div>
</body>
<script>
    var burgerBtn = document.getElementById("burger-btn");
    var navContainer = document.getElementById("nav-container");

    burgerBtn.addEventListener("click", function () {
        if (navContainer.classList.contains('animate-slide-out') || navContainer.classList.contains('hidden')) {
            navContainer.classList.remove('animate-slide-out', 'hidden');
            navContainer.classList.add('animate-slide-in');
        } else {
            navContainer.classList.remove('animate-slide-in');
            navContainer.classList.add('animate-slide-out');

            // To hide the menu after the animation completes
            setTimeout(function () {
                navContainer.classList.add('hidden');
            }, 500);
        }
    });

</script>
</html>