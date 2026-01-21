{
    php-version ? 8.3,
    with-blackfire ? false,
    with-xdebug ? false,
    with-pcov ? !with-blackfire,
}:

let
    nixpkgs = fetchTarball {
        url = if php-version == 8.5 then
            "https://github.com/NixOS/nixpkgs/archive/refs/pull/422308/head.tar.gz"
        else
            "https://github.com/NixOS/nixpkgs/archive/66a437ebcf6160152336e801a7ec289ba2aba3c5.tar.gz";
    };

    pkgs = import nixpkgs {
        config = {
            allowUnfree = true;
        };
    };

    base-php = if php-version == 8.3 then
        pkgs.php83
    else if php-version == 8.4 then
        pkgs.php84
    else if php-version == 8.5 then
        pkgs.php85
    else
        throw "Unknown php version ${php-version}";


    php = pkgs.callPackage ./.nix/pkgs/aeon-php/package.nix {
        php = base-php;
        inherit with-pcov with-xdebug with-blackfire;
    };
in
pkgs.mkShell {
    buildInputs = [
        php
        php.packages.composer
        pkgs.starship
        pkgs.figlet
        pkgs.symfony-cli
        pkgs.act
    ]
        ++ pkgs.lib.optional with-blackfire pkgs.blackfire
    ;

    shellHook = ''
    if [ -f "$PWD/.nix/shell/starship.toml" ]; then
        export STARSHIP_CONFIG="$PWD/.nix/shell/starship.toml"
    else
        export STARSHIP_CONFIG="$PWD/.nix/shell/starship.toml.dist"
    fi

    eval "$(${pkgs.starship}/bin/starship init bash)"

    clear
    figlet "Aeon PHP"
    '';
}