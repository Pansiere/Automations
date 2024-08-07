export ZSH="$HOME/.oh-my-zsh"

ZSH_THEME="crcandy"

alias CC="C && P"
alias F="git add . && git commit -m "update" && git push"
alias D="docker compose down"
alias U="docker compose up -d"
alias DD="D && U"
alias watch="watch -n1"
alias S="tsh ls"
alias s="tsh ssh"
alias speedtest='curl -s https://raw.githubusercontent.com/sivel/speedtest-cli/master/speedtest.py | python3'
alias wtr='curl wttr.in'
alias span='ssh pansiere@66.228.61.207'
alias sd12='ssh root@d12'
alias sdocker='ssh root@docker'
alias skali='ssh root@kali'
alias su22='ssh root@u22'
alias sd11='ssh root@d11'
alias TT='shutdown now; poweroff'
alias k='kubectl'
alias i='ip -c -br a'
alias ls='exa'
alias gh='history|grep'
alias mkdir='mkdir -pv'
alias rm='rm -rf'
alias filesize='du -sh * | sort -h'
alias upd='apt update && apt upgrade -y'
alias df='df -h'
alias c='clear'
alias h='history'
alias ports='netstat -tulanp'
alias top='btop --utf-force'
alias G='sudo apt install'
alias rr='python3 ~/ranger/ranger.py'
alias C='./.git_check_updates.sh'
alias P='./.git_pull.sh'
alias nn='ncdu' #Ncdu is a disk usage analyzer with an ncurses interface. It is designed to find space hogs on a remote server where you don_t have an entire graphical setup available, but it is a useful tool even on regular desktop systems. Ncdu aims to be fast, simple and easy to use, and should be able to run in any minimal POSIX-like environment with ncurses installed.

alias UPD="cd /tmp && \
git clone https://github.com/Pansiere/Automations && \
chmod +x Automations/zsh/update.sh && \
cd - && \
/tmp/Automations/zsh/update.sh && \
source ~/.zshrc && \
c"

alias GIT="cd /tmp && \
git clone https://github.com/Pansiere/Automations.git && \
chmod +x Automations/git\ auto\ pull/git_auto_pull.sh && \
cd - && \
/tmp/Automations/git\ auto\ pull/git_auto_pull.sh && \
c"

alias TMUX="cd && git clone https://github.com/Pansiere/Automations.git && \
cd Automations/zsh/ && \ 
chmod +x tmux-conf.sh && \
./tmux-conf.sh && \
cd -"

alias SSH="cd /tmp && \
git clone git@github.com:Pansiere/ssh.git && \
git clone https://github.com/Pansiere/Automations.git && \
chmod +x /tmp/Automations/zsh/ssh.sh && \
cd - && \
/tmp/Automations/zsh/ssh.sh"

plugins=(git)
plugins=(
    zsh-autosuggestions
)
source $ZSH/oh-my-zsh.sh

if [[ ! -f $HOME/.local/share/zinit/zinit.git/zinit.zsh ]]; then
    print -P "%F{33} %F{220}Installing %F{33}ZDHARMA-CONTINUUM%F{220} Initiative Plugin Manager (%F{33}zdharma-continuum/zinit%F{220})…%f"
    command mkdir -p "$HOME/.local/share/zinit" && command chmod g-rwX "$HOME/.local/share/zinit"
    command git clone https://github.com/zdharma-continuum/zinit "$HOME/.local/share/zinit/zinit.git" && \
        print -P "%F{33} %F{34}Installation successful.%f%b" || \
        print -P "%F{160} The clone has failed.%f%b"
fi

source "$HOME/.local/share/zinit/zinit.git/zinit.zsh"
autoload -Uz _zinit
(( ${+_comps} )) && _comps[zinit]=_zinit

zinit light-mode for \
    zdharma-continuum/zinit-annex-as-monitor \
    zdharma-continuum/zinit-annex-bin-gem-node \
    zdharma-continuum/zinit-annex-patch-dl \
    zdharma-continuum/zinit-annex-rust

zinit light zdharma-continuum/fast-syntax-highlighting
zinit light zsh-users/zsh-completions

neofetch

i
